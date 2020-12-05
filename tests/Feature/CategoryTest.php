<?php

namespace Tests\Feature;

use App\Exceptions\Message;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    const URL = '/api/categories/';
    const HEADERS = ['Accept' => 'application/json'];

    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function indexHappyPath()
    {
        $response = $this->get(CategoryTest::URL, CategoryTest::HEADERS);
        $response->assertOk();
        $response->assertJson(Category::all()->toArray());
    }

    /** @test */
    public function showHappyPath()
    {
        $category = Category::factory()->create();
        $response = $this->get(CategoryTest::URL . $category->id, CategoryTest::HEADERS);
        $response->assertSimilarJson($category->toArray());
        $response->assertOk();
    }

    /** @test */
    public function showIdDoesntExist()
    {
        $category = Category::factory()->create();
        $wrongId = $category->id + 1;
        $response = $this->get(CategoryTest::URL . $wrongId, CategoryTest::HEADERS);
        $this->assertEquals(null, $response->getContent());
        $response->assertNoContent();
    }

    /** @test */
    public function storeCategoryHappyPath()
    {
        $response = $this->post(CategoryTest::URL,
            ['name' => $this->faker->name],
            CategoryTest::HEADERS
        );

        $response->assertCreated();
        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function storeCategoryWithNoName()
    {
        $response = $this->post(CategoryTest::URL,
            ['name' => ''],
            CategoryTest::HEADERS
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
        $this->assertEmpty(Category::all());
    }

    /** @test */
    public function updateCategoryHappyPath()
    {
        $category = Category::create([
            'name' => 'old category'
        ]);
        $expected_name = 'new category';
        $response = $this->put(CategoryTest::URL . $category->id,
            ['name' => $expected_name],
            CategoryTest::HEADERS
        );
        $actual_category = Category::find($category->id);

        $response->assertOk();
        $this->assertEquals($expected_name, $actual_category->name);
    }

    /** @test */
    public function updateCategoryDoesntExist()
    {
        $expected_name = 'old category';
        $category = Category::create([
            'name' => $expected_name
        ]);
        $category_id = $category->id + 1;
        $response = $this->put(CategoryTest::URL . $category_id,
            ['name' => $expected_name],
            CategoryTest::HEADERS
        );
        $actual_category = Category::find($category->id);
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_UPDATE);
        $this->assertEquals($expected_name, $actual_category->name);
    }

    /** @test */
    public function updateCategoryWithNoName()
    {
        $category = Category::create([
            'name' => 'old category'
        ]);
        $response = $this->put(CategoryTest::URL . $category->id,
            ['name' => ''],
            CategoryTest::HEADERS
        );
        $actual_category = Category::find($category->id);
        $this->assertEquals($actual_category->toArray(), $category->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
    }

    /** @test */
    public function deleteCategoryHappyPath()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create();
        $product->category()->associate($category)->save();
        $response = $this->delete(CategoryTest::URL . $category->id,
            [],
            CategoryTest::HEADERS
        );
        $products = Product::where(['category_id' => $category->id])->get();

        $this->assertEmpty($products->toArray());
        $this->assertNull(Category::find($category->id));
        $response->assertOk();
    }

    /** @test */
    public function deleteCategoryUnhappyPath()
    {
        $category = Category::factory()->create();
        $category_id = $category->id + 1;
        $response = $this->delete(CategoryTest::URL . $category_id,
            [],
            CategoryTest::HEADERS
        );
        $this->assertNotNull(Category::find($category->id));
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_DELETED);
    }
}
