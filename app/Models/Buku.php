<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buku extends Model
{
    use HasFactory;
    protected $table = "buku";
    protected $guarded = [];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function peminjaman()
    {
        return $this->belongsToMany(Peminjaman::class, 'buku_yang_dipinjam', 'peminjaman_id', 'buku_id')->withTimestamps();
    }
}
