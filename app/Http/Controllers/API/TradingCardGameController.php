<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Message;
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
        //TODO add validation to all models
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
            return response(null, Response::HTTP_NO_CONTENT);
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
        //TODO add status to all functions of all models
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
        $trading_card_game = TradingCardGame::find($id);
        if ($trading_card_game) {
            TradingCardGame::destroy($id);
            $trading_card_game->products()->update(['trading_card_game_id' => null]);
            $status = Response::HTTP_OK;
            return response(null, $status);
        } else {
            $status = Response::HTTP_NOT_FOUND;
            return response(Message::FAILED_DELETED, $status);
        }
    }
}
