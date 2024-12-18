<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $product = Product::all();

        foreach ($users as $user) {
            Cart::create([
                "user_id"=> $user->id,
                "product_id"=> $product->random()->id,
                 'quantity' => rand(1,10),
                 'total_price'=> rand(100,1000),
                 'status'=> 'pending'
            ]);
        }
    }
}
