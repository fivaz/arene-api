<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'minimum_stock',
        'category_id',
        'trading_card_game_id',
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function trading_card_game()
    {
        return $this->belongsTo('App\Models\TradingCardGame');
    }
}
