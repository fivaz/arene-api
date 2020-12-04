<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function createCategoryHappyPath()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/categories', [
            'name' => 'first Category'
        ]);

        $response->assertCreated();
        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function categoryMustHaveAName()
    {
        $response = $this->post('/api/categories', [
            'name' => ''
        ]);

        $response->assertStatus(400);
        $response->assertSeeText('name');
    }
}
