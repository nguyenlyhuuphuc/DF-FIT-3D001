<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $datas = DB::table('order')
        ->select('status', DB::raw('COUNT(status) as total_order'))
        ->groupBy('status')
        ->get();

        $result = [];
        $result[] = ['Status', 'Total'];
        foreach ($datas as $data) {
            $result[] = [ucfirst($data->status), $data->total_order];
        }

        return view('admin.pages.dashboard.index', ['result' => $result]);
    }
}
