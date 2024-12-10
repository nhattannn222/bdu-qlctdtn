<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TieuChi extends Model
{
    use HasFactory;
    protected $table = 'tieu_chi';
    protected $primaryKey = 'ma_tieu_chi';

    public function tieuChuan()
    {
        return $this->belongsTo(TieuChuan::class, 'ma_tieu_chuan');
    }

    public function minhChung()
    {
        return $this->hasMany(MinhChung::class, 'ma_tieu_chi');
    }
}
