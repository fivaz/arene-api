<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

class CategoryTest extends TestCase
{
    const URL = '/api/categories/';

    use RefreshDatabase;

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
        $wrongId = $category->id+1;
        $response = $this->get(CategoryTest::URL.$wrongId);
        assertEquals(null, $response->getContent());
        $response->assertNoContent();
    }

    /** @test */
    public function storeCategoryHappyPath()
    {
        $this->withoutExceptionHandling();
        $response = $this->post(CategoryTest::URL, [
            'name' => 'first Category'
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
    }
}
