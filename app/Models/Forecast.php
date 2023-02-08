<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    public function getForecast()
    {
        return Forecast::join('prefectures','forecasts.fk_JIS_ac','=','prefectures.JIS_ac')
               ->select('prefectJP','weather','temp_max','temp_min','rain_per','weather_date')
               ->get()
               ->all();
    }
}
