<?php

namespace App\Repositories;

use App\Models\Forecast;

class ForecastEloquentRepository implements ForecastsRepositoryInterface
{
    public function getFirstForecastById()
    {
        return Forecast::orderBy('weather_date','asc')->first();
    }
    
    public function truncateForecast()
    {
        return Forecast::truncate();
    }
    
    public function deleteForecastById()
    {
        return Forecast::whereIn('weather_id',$delete)->delete();
    }
    
    public function decrementForecastById()
    {
        return Forecast::decrement('weather_id',$day);
    }
    
    public function getForecast()
    {
        return Forecast::join('prefectures','forecasts.fk_JIS_ac','=','prefectures.JIS_ac')
               ->select('prefectJP','weather','temp_max','temp_min','rain_per','weather_date')
               ->get()
               ->all();
    }
    
}
