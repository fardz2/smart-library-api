<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PeminjamanControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_peminjaman_buku(): void
    {
        $data = [
            'email' => 'john.doe2@example.com',
            'password' => '@JohnDoe123',
        ];
        $login_response = $this->POST('/api/login', $data);
        $accessToken = $login_response['token']; // Menangkap access token

        $dataPeminjaman = [
            "buku" => [2]
        ];
        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post('/api/peminjaman', $dataPeminjaman);
        $insertResponse->assertStatus(200);
    }

    public function test_pengembalian_buku(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);
        $accessToken = $login_response['token']; // Menangkap access token


        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('/api/peminjaman/buku/1');
        $insertResponse->assertStatus(200);
    }
}
