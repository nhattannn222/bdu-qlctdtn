<?php
namespace App\Http\Controllers;
use App\Models\Khoa;
use App\Models\TieuChuan;
use App\Models\TieuChi;
use App\Models\MinhChung;
use App\Models\MinhChungCon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MappingController extends Controller
{
    public function index()
    {
        $tieuChuan = DB::table('tieu_chuan')
        ->join('khoa', 'tieu_chuan.ma_khoa', '=', 'khoa.ma_khoa')
        ->select('tieu_chuan.ma_tieu_chuan', 'tieu_chuan.ten_tieu_chuan', 'khoa.ten_khoa')
        ->get();

    $data = [];
    foreach ($tieuChuan as $tieuChuanItem) {
        $tieuChi = DB::table('tieu_chi')
            ->where('ma_tieu_chuan', $tieuChuanItem->ma_tieu_chuan)
            ->get();

        $tieuChis = [];
        foreach ($tieuChi as $tieuChiItem) {
            $minhChung = DB::table('minh_chung')
                ->where('ma_tieu_chi', $tieuChiItem->ma_tieu_chi)
                ->get();

            $minhChungs = [];
            foreach ($minhChung as $minhChungItem) {
                $minhChungCon = DB::table('minh_chung_con')
                    ->where('ma_minh_chung', $minhChungItem->ma_minh_chung)
                    ->get();

                $minhChungCons = [];
                foreach ($minhChungCon as $proof) {
                    $minhChungCons[] = [
                        'so_minh_chung' => $proof->so_minh_chung,
                        'ten_minh_chung'   => $proof->ten_minh_chung,
                        'ngay_ban_hanh'         => $proof->ngay_ban_hanh,
                        'noi_ban_hanh'        => $proof->noi_ban_hanh,
                        'link'         => $proof->link,
                    ];
                }

                $minhChungs[] = [
                    'so_thu_tu'         => $minhChungItem->so_thu_tu,
                    'ma_minh_chung' => $minhChungItem->ma_minh_chung,
                    'minhChungCons'=> $minhChungCons,
                ];
            }

            $tieuChis[] = [
                'ma_tieu_chi'   => $tieuChiItem->ma_tieu_chi,
                'mo_ta'=> $tieuChiItem->mo_ta,
                'minhChungs'      => $minhChungs,
            ];
        }
        
        $data[] = [
            'ten_tieu_chuan' => $tieuChuanItem->ten_tieu_chuan,
            'tieuChis'  => $tieuChis,
        ];
    }
    //return response() -> json($data);
    return view('index', ['data' => $data]);
    }
    
}
