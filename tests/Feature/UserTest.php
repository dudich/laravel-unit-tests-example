<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;
    use CreateDataTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_user()
    {
        $user = User::factory()->make();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
        ];

        $response = $this->post('/register', $data);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        $this->post('/logout', $data);

        $user = User::first();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
        ];

        $response = $this->post('/register', $data);
        $response->assertSessionHasErrors(['name', 'email']);
        $response->assertStatus(302);
        $this->assertGuest();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_user()
    {
        $user = $this->createUser();
        $this->be($user);

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        $response = $this->post("/user", $data);
        $response->assertRedirect(RouteServiceProvider::HOME);

        $data['id']++;
        $response = $this->post("/user", $data);
        $response->assertSessionHasErrors(['id']);
        $response->assertStatus(302);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_user()
    {

        $user = $this->createUser();
        $this->be($user);

        $data = [
            'id' => $user->id + 1,
        ];
        $response = $this->delete("/user", $data);
        $response->assertSessionHasErrors(['id']);
        $response->assertStatus(302);
    }

}
