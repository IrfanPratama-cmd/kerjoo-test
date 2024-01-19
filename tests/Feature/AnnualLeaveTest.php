<?php

namespace Tests\Feature;

use App\Models\AnnualLeave;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AnnualLeaveTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetLeaveType()
    {
        // Arrange
        // You can add leave types to the database for testing
        LeaveType::create(['name' => 'Vacation']);

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        // Act
        $response = $this->json('GET', '/api/leave-types');

        // Assert
        $response->assertStatus(200);

    }

    public function testPostAnnualLeave()
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

        $data = [
            'leave_type_id' => 1,
            'description' => 'Test description',
            'start_date' => '2024-01-20',
            'end_date' => '2024-01-22',
        ];

        $response = $this->json('POST', '/api/annual-leaves', $data);

        $response->assertStatus(200) // Assuming 200 is the status code for success
            ->assertJson([
                'employee_id' => $employee->id,
                'leave_type_id' => $data['leave_type_id'],
                'description' => $data['description'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => 'menunggu',
            ]);
    }

    public function testGetAnnualLeave()
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

        $data = AnnualLeave::create([
            'employee_id' => $employee->id,
            'leave_type_id' => 1,
            'description' => 'Test description',
            'start_date' => '2024-01-20',
            'end_date' => '2024-01-22',
            'status' => 'menunggu'
        ]);

        // Melakukan autentikasi sebagai user
        Auth::login($user);

        $response = $this->json('GET', '/api/annual-leaves');

        $response->assertStatus(200);
    }

    public function testGetAnnualLeaveID()
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

        $data = AnnualLeave::create([
            'employee_id' => $employee->id,
            'leave_type_id' => 1,
            'description' => 'Test description',
            'start_date' => '2024-01-20',
            'end_date' => '2024-01-22',
            'status' => 'menunggu'
        ]);

        // Melakukan autentikasi sebagai user
        Auth::login($user);

        $response = $this->json('GET', "/api/annual-leaves/{$data->id}");

        $response->assertStatus(200);
    }

}
