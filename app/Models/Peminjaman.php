<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = "peminjaman";
    protected $primaryKey = 'peminjaman_id';
    public $incrementing = false;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peminjamanBuku()
    {
        return $this->belongsToMany(Buku::class, 'buku_yang_dipinjam', 'peminjaman_id', 'buku_id')->withPivot('id', 'user_id', 'status')->withTimestamps();
    }
}
