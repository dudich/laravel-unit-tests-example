<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;
    use CreateDataTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_listing()
    {
        $user = $this->createUser();

        for ($i = 0; $i < 5; $i++) {
            $this->createProduct($user->id);
        }
        $data = [
            'order' => 'title',
            'direction' => 'asc',
        ];
        $url = '/products?' . http_build_query($data);
        $response = $this->getJson($url, $data);
        $realProductsIds = Product::limit(10)
            ->orderBy($data['order'], $data['direction'])
            ->pluck('id');
        $responseProductsIds = collect(json_decode($response->getContent()))->pluck('id');
        $this->assertEmpty($realProductsIds->diff($responseProductsIds));
    }

    public function test_creation()
    {
        $user = $this->createUser();
        $this->be($user);

        $product = Product::factory()->make();

        $data = [
            'user_id' => $user->id,
            'title' => $product->title,
            'price' => $product->price,
        ];

        $response = $this->postJson('/product', $data);
        $response->assertJsonMissingValidationErrors();

        $data = [
            'user_id' => $user->id,
        ];
        $response = $this->postJson('/product', $data);
        $response->assertJsonValidationErrors(['title', 'price']);
        $response->assertStatus(422);
    }

    public function test_update_product()
    {
        $user = $this->createUser();
        $this->be($user);

        $product = $this->createProduct($user->id);

        $data = [
            'id' => $product->id,
            'user_id' => $user->id,
            'title' => $product->title,
            'price' => $product->price,
        ];

        $response = $this->putJson('/product', $data);
        $response->assertJsonMissingValidationErrors();

        $data = [
            'id' => $product->id,
            'user_id' => $user->id++,
            'title' => $product->title,
            'price' => $product->price,
        ];

        $response = $this->putJson('/product', $data);
        $response->assertJsonValidationErrors(['user_id']);
        $response->assertStatus(403);

    }

    public function test_delete_product()
    {
        $user = $this->createUser();
        $this->be($user);

        $product = $this->createProduct($user->id);

        $data = [
            'id' => $product->id,
            'user_id' => $user->id++,
            'title' => $product->title,
            'price' => $product->price,
        ];

        $response = $this->deleteJson('/product', $data);
        $response->assertJsonValidationErrors(['user_id']);
        $response->assertStatus(403);

        $data = [
            'id' => $product->id,
            'user_id' => $user->id,
            'title' => $product->title,
            'price' => $product->price,
        ];

        $response = $this->deleteJson('/product', $data);
        $response->assertJsonMissingValidationErrors();
    }
}
