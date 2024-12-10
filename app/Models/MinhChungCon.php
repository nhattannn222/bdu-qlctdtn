<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinhChungCon extends Model
{
    use HasFactory;

    
    protected $table = 'minh_chung_con';
    protected $primaryKey = 'ma_minh_chung_con';

    public function minhChung()
    {
        return $this->belongsTo(MinhChung::class, 'ma_minh_chung');
    }
}
