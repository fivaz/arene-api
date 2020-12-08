<?php

namespace Tests\Feature;

use App\Exceptions\Message;
use App\Models\TradingCardGame;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TradingCardGameTest extends TestCase
{
    const URL = '/api/tcgames/';
    const HEADERS = ['Accept' => 'application/json'];

    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function indexHappyPath()
    {
        $response = $this->get(TradingCardGameTest::URL, TradingCardGameTest::HEADERS);
        $response->assertOk();
        $response->assertJson(TradingCardGame::all()->toArray());
    }

    /** @test */
    public function showHappyPath()
    {
        $trading_card_game = TradingCardGame::factory()->create();
        $response = $this->get(TradingCardGameTest::URL . $trading_card_game->id, TradingCardGameTest::HEADERS);
        $response->assertSimilarJson($trading_card_game->toArray());
        $response->assertOk();
    }

    /** @test */
    public function showIdDoesntExist()
    {
        $trading_card_game = TradingCardGame::factory()->create();
        $wrongId = $trading_card_game->id + 1;
        $response = $this->get(TradingCardGameTest::URL . $wrongId, TradingCardGameTest::HEADERS);
        $this->assertEquals(null, $response->getContent());
        $response->assertNoContent();
    }

    /** @test */
    public function storeTradingCardGameHappyPath()
    {
        $response = $this->post(TradingCardGameTest::URL,
            ['name' => $this->faker->name],
            TradingCardGameTest::HEADERS
        );

        $response->assertCreated();
        $this->assertCount(1, TradingCardGame::all());
    }

    /** @test */
    public function storeTradingCardGameWithNoName()
    {
        $response = $this->post(TradingCardGameTest::URL,
            ['name' => ''],
            TradingCardGameTest::HEADERS
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
        $this->assertEmpty(TradingCardGame::all());
    }

    /** @test */
    public function updateTradingCardGameHappyPath()
    {
        $trading_card_game = TradingCardGame::create([
            'name' => 'old trading_card_game'
        ]);
        $expected_name = 'new trading_card_game';
        $response = $this->put(TradingCardGameTest::URL . $trading_card_game->id,
            ['name' => $expected_name],
            TradingCardGameTest::HEADERS
        );
        $actual_trading_card_game = TradingCardGame::find($trading_card_game->id);

        $response->assertOk();
        $this->assertEquals($expected_name, $actual_trading_card_game->name);
    }

    /** @test */
    public function updateTradingCardGameDoesntExist()
    {
        $expected_name = 'old trading_card_game';
        $trading_card_game = TradingCardGame::create([
            'name' => $expected_name
        ]);
        $trading_card_game_id = $trading_card_game->id + 1;
        $response = $this->put(TradingCardGameTest::URL . $trading_card_game_id,
            ['name' => $expected_name],
            TradingCardGameTest::HEADERS
        );
        $actual_trading_card_game = TradingCardGame::find($trading_card_game->id);
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_UPDATE);
        $this->assertEquals($expected_name, $actual_trading_card_game->name);
    }

    /** @test */
    public function updateTradingCardGameWithNoName()
    {
        $trading_card_game = TradingCardGame::create([
            'name' => 'old trading_card_game'
        ]);
        $response = $this->put(TradingCardGameTest::URL . $trading_card_game->id,
            ['name' => ''],
            TradingCardGameTest::HEADERS
        );
        $actual_trading_card_game = TradingCardGame::find($trading_card_game->id);
        $this->assertEquals($actual_trading_card_game->toArray(), $trading_card_game->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
    }

    /** @test */
    public function deleteTradingCardGameHappyPath()
    {
        $trading_card_game = TradingCardGame::factory()->create();
        $product = Product::factory()->create();
        $product->trading_card_game()->associate($trading_card_game)->save();
        $response = $this->delete(TradingCardGameTest::URL . $trading_card_game->id,
            [],
            TradingCardGameTest::HEADERS
        );
        $products = Product::where(['trading_card_game_id' => $trading_card_game->id])->get();

        $this->assertEmpty($products->toArray());
        $this->assertNull(TradingCardGame::find($trading_card_game->id));
        $response->assertOk();
    }

    /** @test */
    public function deleteTradingCardGameUnhappyPath()
    {
        $trading_card_game = TradingCardGame::factory()->create();
        $trading_card_game_id = $trading_card_game->id + 1;
        $response = $this->delete(TradingCardGameTest::URL . $trading_card_game_id,
            [],
            TradingCardGameTest::HEADERS
        );
        $this->assertNotNull(TradingCardGame::find($trading_card_game->id));
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_DELETED);
    }
}
