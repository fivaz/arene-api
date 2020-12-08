<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer',
            'minimum_stock' => 'nullable|integer',
            'category_id' => 'exists:categories,id',
            'trading_card_game_id' => 'exists:trading_card_games,id',
            'language_id' => 'exists:languages,id',
        ];
    }
}
