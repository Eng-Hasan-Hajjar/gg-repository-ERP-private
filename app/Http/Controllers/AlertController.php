<?php


namespace App\Http\Controllers;

use App\Services\Reports\ReportsService;

class AlertController extends Controller
{
    public function navbar(ReportsService $service)
    {
        return response()->json(
            $service->navbarAlerts()
        );
    }
}
