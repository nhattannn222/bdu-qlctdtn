<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinhChung extends Model
{
    use HasFactory;
    protected $table = 'minh_chung';
    protected $primaryKey = 'ma_minh_chung';

    public function tieuChi()
    {
        return $this->belongsTo(TieuChi::class, 'ma_tieu_chi');
    }

    public function minhChungCon()
    {
        return $this->hasMany(MinhChungCon::class, 'ma_minh_chung');
    }
}
