<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuYangDipinjam extends Model
{
    use HasFactory;
    protected $table = "buku_yang_dipinjam";
    protected $guarded = [];
}
