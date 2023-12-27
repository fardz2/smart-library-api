<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    /**
     * A basic feature test example.
     */

    public function test_tambah_user_pustakawan(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token

        // Melakukan operasi insert, update, atau delete sebagai admin dengan menggunakan access token

        $dataTambah = [
            'name' => 'pustakawan',
            'email' => 'pustakawan@gmail.com',
            'password' => '@Pustakawan123',
            'role_id' => 2
        ];
        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post('/api/user', $dataTambah);

        $insertResponse->assertStatus(200);
    }

    public function test_tambah_user_admin(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token

        // Melakukan operasi insert, update, atau delete sebagai admin dengan menggunakan access token

        $dataTambah = [
            'name' => 'admin1',
            'email' => 'admin1@gmail.com',
            'password' => '@Admin1123',
            'role_id' => 1
        ];
        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post('/api/user', $dataTambah);

        $insertResponse->assertStatus(200);
    }

    public function test_edit_user_pustakawan_menjadi_admin(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token

        // Melakukan operasi insert, update, atau delete sebagai admin dengan menggunakan access token

        $edit = [
            'name' => 'pustakawan',
            'email' => 'pustakawan@gmail.com',
            'password' => '@Pustakawan123',
            'role_id' => 1
        ];
        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->put('/api/user/4', $edit);

        $insertResponse->assertStatus(200);
    }
    public function test_edit_user_pengunjung_menjadi_pustakawan(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token

        // Melakukan operasi insert, update, atau delete sebagai admin dengan menggunakan access token

        $edit = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => '@JohnDoe123',
            'role_id' => 2
        ];
        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->put('/api/user/2', $edit);

        $insertResponse->assertStatus(200);
    }
}
