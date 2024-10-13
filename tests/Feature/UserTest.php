<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_store_user(){
        $data = [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'password' => 'password', // Ensure the password passes validation
        ];

        $user = $this->postJson(route('user.store'), $data)->assertOk()->json();
        $this->assertDatabaseHas('users', ['email' => $data['email'], 'phone' => $data['phone']]);
        $this->assertEquals('success', $user['status']);
        $this->assertEquals($user['data']['firstname'], $data['firstname']);
    }

    public function test_fetch_user(){
        $user = User::factory()->create();

        $fetched = $this->getJson(route('user.show', $user->id))->assertOk()->json();
        $this->assertEquals('success', $fetched['status']);
        $this->assertEquals($user->firstname, $fetched['data']['firstname']);
    }

    public function test_update_user(){
        $user = User::factory()->create();
        $data = [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker()->email(),
            'phone' => $this->faker()->phoneNumber(),
        ];
        $update = $this->putJson(route('user.update', $user->id), $data)->assertOk()->json();
        $this->assertDatabaseHas('users', ['firstname' => $data['firstname']]);
    }

    public function test_delete_user(){
        $user = User::factory()->create();
        $this->deleteJson(route('user.delete', $user->id))->assertOk();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
