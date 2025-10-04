<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnalyticsExport;

class AnalyticsController extends Controller
{
    public function pdf()
    {
        $data = $this->getAnalyticsData();
        $pdf = Pdf::loadView('exports.analytics_pdf', $data);
        return $pdf->download('analyses-eventia.pdf');
    }

    public function excel()
    {
        return Excel::download(new AnalyticsExport, 'analyses-eventia.xlsx');
    }

    private function getAnalyticsData()
    {
        return [
            'totalUsers'   => User::count(),
            'totalEvents'  => Evenement::count(),
            'totalRevenue' => Evenement::sum('tarif'),
            'avgFillRate'  => Evenement::avg('places_max') > 0
                ? round(Evenement::avg('inscrits_count') / Evenement::avg('places_max') * 100, 1)
                : 0,
            'topEvents'    => Evenement::withCount('participants')->orderByDesc('participants_count')->take(5)->get(),
            'roles'        => [
                'participant'  => User::where('role', 'participant')->count(),
                'organisateur' => User::where('role', 'organisateur')->count(),
                'admin'        => User::where('role', 'admin')->count(),
            ],
        ];
    }
}
