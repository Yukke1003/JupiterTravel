<?php
namespace App\Services;

use App\Services\ExcuteAPIService;

use DateTime;

use DateTimeZone;

class WeatherDateService extends ExecuteAPIService
{
    protected $api;
    
    public function __construct(ExecuteAPIService $execute_api)
    {
        $this->api = $execute_api;
    }
    
    //天気情報の取得
    public function weatherDateService($target_prefect,$num){
        
        //天気情報web Apiの準備
        $weather_res = null;
        $url = config('services.weather_api.weather_date_url');
        $params = [
            'lat' => $target_prefect->lat,
            'lon' => $target_prefect->lon,
            'lang' => 'ja',
            'units' => 'metric',
            'appid' => config('services.weather_api.weather_key'),
            'exclude' => 'current,hourly,minutely',
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
        $each_date = array();
        //デフォルトのタイムゾーン
        date_default_timezone_set('Asia/Tokyo');
    
        for($i=7;$num<$i;$i--){
                
        //天気情報
        array_push($weather, $weather_res['daily'][$i]['weather'][0]['description']);
        //最高気温
        array_push($temp_max, $weather_res['daily'][$i]['temp']['max']);
        //最低気温
        array_push($temp_min, $weather_res['daily'][$i]['temp']['min']);
        //降水確率
        array_push($rain_per, $weather_res['daily'][$i]['pop']);
        //UTC時間からJST時間に変換して格納
        $unix_time = $weather_res['daily'][$i]['dt'];
        $date = new DateTime('@' . $unix_time);
        $jst_time = $date->setTimezone(new DateTimeZone('Asia/Tokyo'))->format('Y-m-d');
        array_push($each_date,$jst_time);
            
        }
            
        $weather_Api_Result = array(
            'weaPre'=>$target_prefect,
            'weather'=>$weather,
            'temp_max'=>$temp_max,
            'temp_min'=>$temp_min,
            'rain_per'=>$rain_per,
            'date' =>$each_date);
         
        return $weather_Api_Result;
        
    }
    
}
