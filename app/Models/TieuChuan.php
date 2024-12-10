<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TieuChuan extends Model
{
    use HasFactory;

    protected $table = 'tieu_chuan';
    protected $primaryKey = 'ma_tieu_chuan';

    public function khoa()
    {
        return $this->belongsTo(Khoa::class, 'ma_khoa');
    }

    public function tieuChi()
    {
        return $this->hasMany(TieuChi::class, 'ma_tieu_chuan');
    }
}
