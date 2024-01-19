<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testRegistration()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123'
        ];

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                'access_token',
                'token_type'
            ]);

        $user = User::where('email', $data['email'])->first();
        $this->assertNotNull($user);

        $this->assertTrue(Hash::check($data['password'], $user->password));

        $employee = Employee::where('user_id', $user->id)->first();
        $this->assertNotNull($employee);
        $this->assertEquals($data['name'], $employee->full_name);
        $this->assertEquals($data['email'], $employee->email);
    }

    public function testLogin()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'password'
        ];

        $response = $this->json('POST', '/api/login', $data);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'access_token',
            'token_type'
        ]);
    }

    public function testLogout()
    {
        // Membuat pengguna dan melakukan autentikasi Sanctum
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        // Memanggil endpoint logout
        $response = $this->postJson('/api/logout');

        // Memastikan bahwa token pengguna dihapus
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => auth()->id(),
        ]);

        // Memeriksa respons JSON yang diharapkan
        $response->assertJson([
            'message' => 'logout success',
        ]);

        // Memeriksa status respons
        $response->assertStatus(200);
    }



}
