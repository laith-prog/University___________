<?php

namespace App\Jobs;

use App\Models\CartItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteCartItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $cartItem;

    public function __construct(CartItem $cartItem)
    {
        $this->cartItem = $cartItem;
    }

    public function handle()
    {
        $cartItem = $this->cartItem;

        // Restore the product's quantity before deleting the cart item
        $product = $cartItem->product;
        $product->quantity += $cartItem->quantity;
        $product->save();

        $cartItem->delete();
    }
}
