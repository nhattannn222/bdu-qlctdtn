<?php

namespace App\Models;
    
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    use HasFactory;

    protected $table = 'khoa';
    protected $primaryKey = 'ma_khoa';

    public function tieuChuan()
    {
        return $this->hasMany(TieuChuan::class, 'ma_khoa');
    }
}
