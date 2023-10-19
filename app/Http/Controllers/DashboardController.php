<?php

namespace App\Http\Controllers;

use App\Services\SibfDolarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use App\Exports\DolarExport;
use Maatwebsite\Excel\Facades\Excel;


class DashboardController extends Controller
{
    protected $sibfDolarService;
    public function __construct(SibfDolarService $sibfDolarService)
    {
        $this->sibfDolarService = $sibfDolarService;
    }
    public function index() {
        if (!Auth::User()->isAdmin()) {
            return Inertia::render('Dashboard/User');
        } else {
            return Inertia::render('Dashboard/Admin');
        }
    }

    public function getPriceByMonth(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $api_key = config("services.sbif_api.key");
        $response = Http::get("https://api.sbif.cl/api-sbifv3/recursos_api/dolar/{$year}/{$month}?apikey={$api_key}&formato=json");
        if($response->successful()) {
            $resp_obj = $response->object();
            $dolar_price_by_month = $resp_obj->Dolares;
            return Inertia::render('Dashboard/Admin', compact('dolar_price_by_month'));
        }
    }

    public function exportToCsv(Request $request) {
        return Excel::download(new DolarExport($request->all()), 'users.xlsx');
    }
}
