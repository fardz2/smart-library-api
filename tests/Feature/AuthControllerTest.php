<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    /**
     * A basic feature test example.
     */


    public function test_pengunjung_registrasi(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => '@JohnDoe123',
        ];

        $response = $this->POST('/api/register', $data);
        $dataBaru = [
            'name' => 'John Doe2',
            'email' => 'john.doe2@example.com',
            'password' => '@JohnDoe123',
        ];

        $this->POST('/api/register', $dataBaru);

        $response->assertStatus(200);
    }
    public function test_pengunjung_login(): void
    {
        $data = [
            'email' => 'john.doe@example.com',
            'password' => '@JohnDoe123',
        ];
        $response = $this->POST('/api/login', $data);

        $response->assertStatus(200);
    }
    public function test_admin_login(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $response = $this->POST('/api/login', $data);

        $response->assertStatus(200);
    }
    public function test_logout(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token
        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('/api/logout');
        $logoutResponse->assertStatus(500);
    }
}
