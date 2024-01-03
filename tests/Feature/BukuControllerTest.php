<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BukuControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_tambah_buku(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token

        // Melakukan operasi insert, update, atau delete sebagai admin dengan menggunakan access token

        $dataTambah = [
            'judul_buku' => "buku test",
            'penerbit' => "test",
            'pengarang' => "test",
            'sinopsis' => "test",
            'tahun_terbit' => "2003-04-10",
            'jumlah_buku' =>  1,
            'lokasi_rak_buku' => "test",
        ];
        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post('/api/buku', $dataTambah);

        $dataTambah = [
            'judul_buku' => "buku test2",
            'penerbit' => "test",
            'pengarang' => "test",
            'sinopsis' => "test",
            'tahun_terbit' => "2003-04-10",
            'jumlah_buku' =>  1,
            'lokasi_rak_buku' => "test",
        ];
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post('/api/buku', $dataTambah);
        $insertResponse->assertStatus(200);
    }

    public function test_tambah_buku_invalid(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token

        // Melakukan operasi insert, update, atau delete sebagai admin dengan menggunakan access token

        $dataTambah = [
            'penerbit' => "test",
            'pengarang' => "test",
            'sinopsis' => "test",
            'tahun_terbit' => "2003-04-10",
            'jumlah_buku' =>  1,
            'lokasi_rak_buku' => "test",
        ];
        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post('/api/buku', $dataTambah);

        $dataTambah = [
            'judul_buku' => "buku test2",
            'penerbit' => "test",
            'pengarang' => "test",
            'sinopsis' => "test",
            'tahun_terbit' => "2003-04-10",
            'jumlah_buku' =>  1,
            'lokasi_rak_buku' => "test",
        ];
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post('/api/buku', $dataTambah);
        $insertResponse->assertStatus(422);
    }

    public function test_edit_buku(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token

        // Melakukan operasi insert, update, atau delete sebagai admin dengan menggunakan access token

        $dataTambah = [
            'judul_buku' => "buku test edit",
            'penerbit' => "test",
            'pengarang' => "test",
            'sinopsis' => "test",
            'tahun_terbit' => "2003-04-10",
            'jumlah_buku' =>  1,
            'lokasi_rak_buku' => "test",
        ];
        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->put('/api/buku/1', $dataTambah);

        $insertResponse->assertStatus(200);
    }
    public function test_edit_buku_invalid(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token

        // Melakukan operasi insert, update, atau delete sebagai admin dengan menggunakan access token

        $dataTambah = [
            'penerbit' => "test",
            'pengarang' => "test",
            'sinopsis' => "test",
            'tahun_terbit' => "2003-04-10",
            'jumlah_buku' =>  1,
            'lokasi_rak_buku' => "test",
        ];
        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->put('/api/buku/1', $dataTambah);

        $insertResponse->assertStatus(422);
    }


    public function test_hapus_buku(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token

        // Melakukan operasi insert, update, atau delete sebagai admin dengan menggunakan access token


        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->delete('/api/buku/1');

        $insertResponse->assertStatus(200);
    }

    public function test_hapus_buku_invalid(): void
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '@Admin123',
        ];
        $login_response = $this->POST('/api/login', $data);

        $accessToken = $login_response['token']; // Menangkap access token

        // Melakukan operasi insert, update, atau delete sebagai admin dengan menggunakan access token


        $insertResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->delete('/api/buku/100');

        $insertResponse->assertStatus(404);
    }
}
