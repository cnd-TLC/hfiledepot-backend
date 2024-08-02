<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseRequest;

class DashboardController extends Controller
{
    public function getDepartmentRequestsStatusCount($status, $year)
    {
        $count = PurchaseRequest::where('status', $status)
                ->whereYear('created_at', $year)
                ->where('department', auth()->user()->department)
                ->count();

        return response()->json(['count' => $count]);
    }

    public function getPendingCount($year)
    {
        $data = PurchaseRequest::selectRaw("
                    COUNT(CASE WHEN approved_by_cbo_name IS NOT NULL THEN 1 END) AS cbo_pending,
                    COUNT(CASE WHEN approved_by_cto_name IS NOT NULL THEN 1 END) AS cto_pending,
                    COUNT(CASE WHEN approved_by_cmo_name IS NOT NULL THEN 1 END) AS cmo_pending,
                    COUNT(CASE WHEN approved_by_bac_name IS NOT NULL THEN 1 END) AS bac_pending,
                    COUNT(CASE WHEN approved_by_cgso_name IS NOT NULL THEN 1 END) AS cgso_pending,
                    COUNT(CASE WHEN approved_by_cao_name IS NOT NULL THEN 1 END) AS cao_pending
                ")
                ->whereYear('created_at', $year)
                ->first();

        return response()->json([
            'cbo_pending' => $data->cbo_pending,
            'cto_pending' => $data->cto_pending,
            'cmo_pending' => $data->cmo_pending,
            'bac_pending' => $data->bac_pending,
            'cgso_pending' => $data->cgso_pending,
            'cao_pending' => $data->cao_pending,
        ]);
    }

    public function getOfficeRequestsCount($year)
    {
        $offices = [
            'City Mayor\'s Office (CMO)',
            'City Administrator\'s Office (CAdmO)',
            'City Tourism Office (CToO)',
            'City Planning and Development Office (CPDO)',
            'City Budget Office (CBO)',
            'City Accountant\'s Office (CAccO)',
            'City General Services Office (CGSO)',
            'City Legal Office (CLO)',
            'City Human Resource Management Office (CHRMO)',
            'City Zoning Administration Office (CZAO)',
            'City Treasurer\'s Office (CTO)',
            'City Assessor\'s Office (CAsO)',
            'City Civil Registrar\'s Office (CCRO)',
            'City Health Office (CHO)',
            'City Social Welfare and Development Office (CSWDO)',
            'City Engineer\'s Office (CEO)',
            'City Agriculture Services Office (CASO)',
            'City Environment and Natural Resources Office (CENRO)',
            'City Veterinary Office (CVO)',
            'City Disaster and Risk Reduction Management Office (CDRRMO)',
            'Permits and Licensing (PL)',
            'Public Information (PI)',
            'BAPAS (BAPAS)',
            'Traffic and Security (TS)',
            'Market Operations (MO)',
            'DILG-Sorsogon City (DILG)',
            'Sangguniang Panlungsod (SP)',
            'City Information and Communications Technology Office (CICTO)',
            'Bids and Awards Committee (BAC)',
        ];

        $requestCounts = PurchaseRequest::select('department', DB::raw('count(*) as total_requests'))
                ->whereYear('created_at', $year)
                ->whereIn('department', $offices) 
                ->groupBy('department')
                ->get();

        $requestCountsArray = $requestCounts->pluck('total_requests', 'department')->toArray();

        return response()->json(['retrievedData' => $requestCountsArray]);
    }


}
