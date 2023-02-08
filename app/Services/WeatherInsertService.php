<?php
namespace App\Services;

use App\Forecast;

use App\Services\WeatherDateService;

class WeatherInsertService
{
    protected $date_service;
    
    public function __construct(WeatherDateService $weatherDateService)
    {
        $this->date_service = $weatherDateService;
    }
    //天気情報の保存
    public function weatherInsert($weather_prefects,$num)
    {
        foreach($weather_prefects as $target){
                $weather_result = $this->date_service->weatherDateService($target,$num);
                $data = [];
                $id = 7;
                    for($i=$num;$i<7;$i++){
                        $data[] = [
                            'forecast_id' => $id."-".$target->JIS_ac,
                            'weather_id' => $id,
                            'fk_JIS_ac' => $target->JIS_ac,
                            'weather' => $weather_result['weather'][$i],
                            'temp_max' => $weather_result['temp_max'][$i],
                            'temp_min' => $weather_result['temp_min'][$i],
                            'rain_per' => $weather_result['rain_per'][$i],
                            'weather_date' => $weather_result['date'][$i],
                            'created_at' => now(),
                            'updated_at' => now()
                            ];
                        $id--;
                    }
                \App\Forecast::insert($data);
            }
    }
    
}
