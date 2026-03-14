<?php


namespace App\Http\Controllers;

use App\Services\Reports\ReportsService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AlertController extends Controller
{
    /*
    public function navbar(ReportsService $service)
    {
        return response()->json(
            $service->navbarAlerts()
        );
    }
*/
    public function navbar(Request $request, ReportsService $service)
    {

        if ($request->get('all')) {

            $alerts = $service->systemAlerts(); // كل الإشعارات

            $alerts = collect($alerts)
                ->filter(function ($a) {

                    if (!isset($a['time']))
                        return false;

                    return Carbon::parse($a['time'])->isToday();
                })
                ->values()
                ->toArray();

        } else {

            $alerts = $service->systemAlerts(5); // القائمة

        }

        $total = count($service->systemAlerts());

        return response()->json([
            'count' => $total,
            'alerts' => $alerts
        ]);

    }


}
