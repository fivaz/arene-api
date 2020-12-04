<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TradingCardGame;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TradingCardGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response(TradingCardGame::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        return response(TradingCardGame::create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        $trading_card_game = TradingCardGame::find($id);
        if ($trading_card_game)
            return response($trading_card_game, Response::HTTP_OK);
        else
            return response(null, 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        $trading_card_game = TradingCardGame::find($id);
        $trading_card_game->update($request->all());
        return response($trading_card_game);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        return response(TradingCardGame::destroy($id));
    }
}
