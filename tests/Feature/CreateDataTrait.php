<?php


namespace Tests\Feature;


use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait CreateDataTrait
{

    protected function createUser()
    {

        $userData = User::factory()->make();

        return User::create([
            'name' => $userData->name,
            'email' => $userData->email,
            'password' => Hash::make($userData->password),
        ]);
    }

    protected function createProduct($userId)
    {

        $productData = Product::factory()->make();

        return Product::create([
            'user_id' => $userId,
            'title' => $productData->title,
            'price' => $productData->price,
        ]);
    }
}
