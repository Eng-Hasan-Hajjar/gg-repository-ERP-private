<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $user = auth()->user();

            if (!$user) {
                return response()->json(['status' => 'unauthenticated'], 401);
            }

            $city = null;
            $country = null;

            // Reverse Geocoding — في try منفصل حتى لا يوقف العملية
            // استبدل هذا الجزء فقط
            try {
                $geo = Http::withHeaders([
                    'User-Agent' => 'NamaaERP/1.0 (contact@namaa.com)',
                ])->timeout(5)->get('https://nominatim.openstreetmap.org/reverse', [
                            'lat' => $request->latitude,
                            'lon' => $request->longitude,
                            'format' => 'json',
                            'accept-language' => 'ar',
                        ]);

                if ($geo->ok()) {
                    $address = $geo->json('address') ?? [];
                    Log::info('Full address data:', $address); // ← هنا داخل if

                    $city = $address['city']
                        ?? $address['town']
                        ?? $address['municipality']
                        ?? $address['city_district']
                        ?? $address['county']
                        ?? $address['state']
                        ?? null;

                    $country = $address['country'] ?? null;

                    // بناء العنوان التفصيلي الكامل
                    $addressParts = collect([
                        $address['road'] ?? null,           // الشارع
                        $address['neighbourhood'] ?? null,  // الحارة
                        $address['suburb'] ?? null,         // الحي
                        $address['quarter'] ?? null,        // المنطقة
                        $address['city_district'] ?? null,  // القسم
                        $city,                              // المدينة
                        $address['state'] ?? null,          // المحافظة
                        $country,                           // الدولة
                    ])->filter()->unique()->implode('، ');

                }
            } catch (\Exception $geoEx) {
                Log::warning('Geocoding failed: ' . $geoEx->getMessage());
            }

            // إيجاد آخر جلسة نشطة
            $session = $user->sessions()
                ->whereNull('logout_at')
                ->latest('login_at')
                ->first();

            if ($session) {
                $session->latitude = $request->latitude;
                $session->longitude = $request->longitude;
                $session->city = $city;
                $session->country = $country;
                $session->address_detail = $addressParts;
                $session->save();
            }

            session(['location_captured' => true]);

            return response()->json([
                'status' => 'ok',
                'city' => $city,
                'country' => $country,
            ]);

        } catch (\Exception $e) {
            Log::error('LocationController@store: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function skip()
    {
        session(['location_captured' => true]);
        return response()->json(['status' => 'skipped']);
    }
}