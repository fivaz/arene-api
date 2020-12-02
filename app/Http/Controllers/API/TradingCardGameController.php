<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TradingCardGame;
use Illuminate\Http\Request;

class TradingCardGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(TradingCardGame::with('products')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response(TradingCardGame::create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trading_card_game = TradingCardGame::find($id);
        $trading_card_game->products;
        if ($trading_card_game)
            return response($trading_card_game, 200);
        else
            return response(null, 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $trading_card_game = TradingCardGame::find($id);
        $trading_card_game->update($request->all());
        return response($trading_card_game);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(TradingCardGame::destroy($id));
    }
}
