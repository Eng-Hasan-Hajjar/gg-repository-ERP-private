<?php

namespace App\Http\Controllers;

use App\Services\Reports\ReportsService;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function navbar(Request $request, ReportsService $service)
    {
        $isAll = $request->boolean('all');

        // كل الإشعارات (مطلوبة دائماً لحساب الأعداد والتصنيف)
        $all = $service->systemAlerts();

        if ($isAll) {
            $alerts = $all;
        } else {
            // القائمة المنسدلة — أول 8 فقط
            $alerts = array_slice($all, 0, 8);
        }

        // ✅ عدد الإشعارات التي "تحتاج إجراء" فقط (للعدّاد الأحمر)
        $actionCount = count(array_filter($all, fn($a) => ($a['category'] ?? 'activity') === 'action'));

        return response()->json([
            'count'        => count($all),   // الإجمالي (للتوافق الخلفي)
            'action_count' => $actionCount,  // ✅ يحتاج إجراء
            'alerts'       => $alerts,
        ]);
    }
}