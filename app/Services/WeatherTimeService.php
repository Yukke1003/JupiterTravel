<?php
namespace App\Services;

use App\Services\ExcuteAPIService;

use DateTime;

use DateTimeZone;

class WeatherTimeService extends ExecuteAPIService
{
    protected $api;
    
    public function __construct(ExecuteAPIService $execute_api)
    {
        $this->api = $execute_api;
    }
    
    //天気情報の取得
    public function weatherTimeService($target_prefect){
        
        //天気情報web Apiの準備
        $weather_res = null;
        $url = config('services.weather_api.weather_time_url');
        $params = [
            'lat' => $target_prefect->lat,
            'lon' => $target_prefect->lon,
            'appid' => config('services.weather_api.weather_key'),
            'lang' => 'ja',
            'units' => 'metric'
            ];
        
        //天気情報Web Apiの実行(open weather map api)
        $response_json = $this->api->execute_Api($url, $params);
        $weather_res = json_decode($response_json, true);
        
        //取得した天気情報を表示するための整理
        //天気
        $weather = array();
        //最高気温
        $temp_max = array();
        //最低気温
        $temp_min = array();
        //降水確率
        $rain_per = array();
        //日付
        $date = array();
        //デフォルトのタイムゾーン
        date_default_timezone_set('Asia/Tokyo');
        
        for($i=0;$i<40;$i++){
            
            //天気の3時間ごとの情報を格納
            array_push($weather, $weather_res['list'][$i]['weather'][0]['description']);
            //最高気温の3時間ごとの情報を格納
            array_push($temp_max, $weather_res['list'][$i]['main']['temp_max']);
            //最低気温の3時間ごとの情報を格納
            array_push($temp_min, $weather_res['list'][$i]['main']['temp_min']);
            //降水確率
            array_push($rain_per,$weather_res['list'][$i]['pop']);
            //UTC時間からJST時間に変換して格納
            $utcDate = new DateTime($weather_res['list'][$i]['dt_txt'], new DateTimeZone('Asia/Tokyo'));
            $jtcDate = $utcDate->modify("+9 hour");
            array_push($date, $jtcDate);
        }
        
        $weather_Api_Result = array(
            'weaPre'=>$target_prefect,
            'weather'=>$weather,
            'temp_max'=>$temp_max,
            'temp_min'=>$temp_min,
            'rain_per'=>$rain_per,
            'date' =>$date);
        dd($weather_Api_Result);    
        
        return $weather_Api_Result;
        
    }
    
}
