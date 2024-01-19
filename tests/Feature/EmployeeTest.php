<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use DatabaseTransactions;

    public function testProfileEmployee()
    {
        $user = User::factory()->create([
            'email' => 'test@mail.com',
            'name' => 'Test',
            'password' => Hash::make('password'),
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone_number' => '0811111',
            'address' => 'Solo'
        ]);

        // Melakukan autentikasi sebagai user
        Auth::login($user);

        // Memanggil endpoint show pada controller
        $response = $this->json('GET', '/api/profile-employees');

        // Memastikan respons memiliki status 200 (OK)
        $response->assertStatus(200);

        // Memastikan data yang dikembalikan sesuai dengan data employee yang diharapkan
        $response->assertJson([
            'id' => $employee->id,
            'user_id' => $employee->user_id,
            'full_name' => $employee->full_name,
            'email' => $employee->email,
            'phone_number' => $employee->phone_number,
            'address' => $employee->address,
        ]);
    }

    public function testUpdateEmployee(){
        $user = User::factory()->create([
            'email' => 'test@mail.com',
            'name' => 'Test',
            'password' => Hash::make('password'),
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone_number' => '0811111',
            'address' => 'Solo'
        ]);

        // Melakukan autentikasi sebagai user
        Auth::login($user);

        $data = [
            'full_name' => 'John Doe',
            'phone_number' => '123456789',
            'address' => '123 Main Street',
        ];

        $response = $this->json('PUT', '/api/update-employees', $data);

        $response->assertStatus(200)
        ->assertJsonStructure([
                'id',
                'user_id',
                'full_name',
                'email',
                'phone_number',
                'address',
                'created_at',
                'updated_at'
        ]);

    }
}
