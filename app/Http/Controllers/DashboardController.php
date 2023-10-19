<?php

namespace App\Http\Controllers;

use App\Services\SibfDolarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $dolar_price_by_month = $this->sibfDolarService->getPriceByMonth($year, $month);
        return Inertia::render('Dashboard/Admin', compact('dolar_price_by_month'));
    }

    public function exportToCsv(Request $request) {
        return Excel::download(new DolarExport($request->all()), 'users.xlsx');
    }
}
