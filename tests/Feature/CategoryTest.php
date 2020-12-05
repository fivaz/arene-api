<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    const URL = '/api/categories/';

    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function indexHappyPath()
    {
        $this->withoutExceptionHandling();
        $response = $this->get(CategoryTest::URL);
        $response->assertOk();
        $response->assertJson(Category::all()->toArray());
    }

    /** @test */
    public function showHappyPath()
    {
        $this->withoutExceptionHandling();
        $category = Category::factory()->create();
        $response = $this->get(CategoryTest::URL . $category->id);
        $response->assertSimilarJson($category->toArray());
        $response->assertOk();
    }

    /** @test */
    public function showIdDoesntExist()
    {
        $this->withoutExceptionHandling();
        $category = Category::factory()->create();
        $wrongId = $category->id + 1;
        $response = $this->get(CategoryTest::URL . $wrongId);
        $this->assertEquals(null, $response->getContent());
        $response->assertNoContent();
    }

    /** @test */
    public function storeCategoryHappyPath()
    {
        $this->withoutExceptionHandling();
        $response = $this->post(CategoryTest::URL, [
            'name' => $this->faker->name
        ]);

        $response->assertCreated();
        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function storeCategoryMustHaveAName()
    {
        $response = $this->post(CategoryTest::URL, [
            'name' => ''
        ]);

        $response->assertStatus(400);
        $response->assertSeeText('name');
        $this->assertEmpty(Category::all());
    }

    /** @test */
    public function updateCategoryHappyPath()
    {
        $this->withoutExceptionHandling();
        $category = Category::create([
            'name' => 'old category'
        ]);
        $expected_name = 'new category';
        $response = $this->put(CategoryTest::URL . $category->id, [
            'name' => $expected_name
        ]);
        $actual_category = Category::find($category->id);

        $response->assertOk();
        $this->assertEquals($expected_name, $actual_category->name);
    }

    /** @test */
    public function updateCategoryUnHappyPath()
    {
        $this->withoutExceptionHandling();
        $expected_name = 'old category';
        $category = Category::create([
            'name' => $expected_name
        ]);
        $category_id = $category->id + 1;
        $response = $this->put(CategoryTest::URL . $category_id, [
            'name' => $expected_name
        ]);
        $actual_category = Category::find($category->id);
        $response->assertNotFound();
        $response->assertJson(['error' => "the resource you're trying to update doesn't exist"]);
        $this->assertEquals($expected_name, $actual_category->name);
    }

    /** @test */
    public function deleteCategoryHappyPath()
    {
        $this->withoutExceptionHandling();
        $category = Category::factory()->create();
        $product = Product::factory()->create();
        $product->category()->associate($category)->save();
        $response = $this->delete(CategoryTest::URL . $category->id);
        $products = Product::where(['category_id' => $category->id])->get();

        $this->assertEmpty($products->toArray());
        $this->assertNull(Category::find($category->id));
        $response->assertOk();
    }

    /** @test */
    public function deleteCategoryUnhappyPath()
    {
        $this->withoutExceptionHandling();
        $category = Category::factory()->create();
        $category_id = $category->id + 1;
        $response = $this->delete(CategoryTest::URL . $category_id);
        $this->assertNotNull(Category::find($category->id));
        $response->assertNotFound();
        $response->assertJson(['error' => "the resource you're trying to delete doesn't exist"]);
    }
}
