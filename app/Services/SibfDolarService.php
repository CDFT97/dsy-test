<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class PagueloFacilService.
 */
class SibfDolarService
{
    public function getPriceByMonth(string $year, string $month)
    {
            $api_key = config("services.sbif_api.key");
            $response = Http::get("https://api.sbif.cl/api-sbifv3/recursos_api/dolar/{$year}/{$month}?apikey={$api_key}&formato=json");
            if($response->successful()) {
                return $response->object()->Dolares;
            } else {
                Log::error($response->body());
                return "[]";
            }
       
    }
}
