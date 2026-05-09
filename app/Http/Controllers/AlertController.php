<?php

namespace App\Http\Controllers;

use App\Services\Reports\ReportsService;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function navbar(Request $request, ReportsService $service)
    {
        $isAll = $request->boolean('all');

        if ($isAll) {
            // ✅ كل الإشعارات بدون limit ولا فلتر تاريخ
            $alerts = $service->systemAlerts();
        } else {
            // ✅ القائمة المنسدلة — أول 8 فقط
            $alerts = $service->systemAlerts(8);
        }

        // العدد الكلي دائماً بدون limit
        $total = count($service->systemAlerts());

        return response()->json([
            'count'  => $total,
            'alerts' => $alerts,
        ]);
    }
}