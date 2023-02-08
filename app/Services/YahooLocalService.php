<?php
namespace App\Services;

use App\Services\ExcuteAPIService;

class YahooLocalService extends ExecuteAPIService
{
    protected $api;
    
    public function __construct(ExecuteAPIService $execute_api)
    {
        $this->api = $execute_api;
    }
    
    //カテゴリー検索結果の情報取得
    public function categoryService($params)
    {
        //Yahoo Local Apiの準備
        static $store_arr = array();
        static $yahoo_result = 0;
        static $results = 11;
        $url = config('services.yahoo_local_api.yahoo_local_url');
        $result_arr = array();
        $yahoo_res = null;
        
        //Yahoo Local Apiの実行(Yahoo!ローカルサーチ API)
        $response_json = $this->api->execute_Api($url, $params);
        $yahoo_res = json_decode($response_json, true);
        //KeyをGidに変換兼重複削除
        $yahoo_res = $yahoo_res['Feature'];
        $yahoo_res = array_column($yahoo_res, null, 'Gid');
        //2回目以降の重複確認
        if($results === 11) {
            $count_res = count($yahoo_res);
            $target_arr = array_keys($yahoo_res);
            for($i=0;$i<$count_res;$i++) {
                array_push($store_arr, $target_arr[$i]);
            }
            $store_arr = array_flip($store_arr);
        }
        else
        {
            $dupplicate_arr = array_diff_key($yahoo_res, $store_arr);
            $count_dup = count($dupplicate_arr);
            $target_arr = array_keys($dupplicate_arr);
            $temp_arr = array();
            for($i=0;$i<$count_dup;$i++) {
                array_push($temp_arr, $target_arr[$i]);
            }
            $flip_arr = array_flip($temp_arr);
            $store_arr += $flip_arr;
        }
        
        //表示したいモノのみ取得
        //施設名
        static $facility_name = array();
        //場所
        static $place = array();
        //電話番号
        static $phone_num = array();
        //最寄駅
        static $nearest_Sta = array();
        
        foreach($target_arr as $arr) {
            if(!isset($yahoo_res[$arr]['Property']['Address'])
            ||(!isset($yahoo_res[$arr]['Property']['Tel1']))
            ||(!isset($yahoo_res[$arr]['Property']['Station'][0])))
            {
                continue;
            }else if($yahoo_result !== 10){
                array_push($facility_name, $yahoo_res[$arr]['Name']);
                array_push($place, $yahoo_res[$arr]['Property']['Address']);
                array_push($phone_num, $yahoo_res[$arr]['Property']['Tel1']);
                array_push($nearest_Sta, $yahoo_res[$arr]['Property']['Station']);
                $yahoo_result++;
            }else{
                break;
            }
        } 
        //重複削除後の要素数
        $yahoo_result = count($facility_name);
        
        //件数10件未満の場合：APIの再実行
        if($yahoo_result < 10){
            $start = $results;
            $results += 10;
            $params += array('start' => $start);
            return $this->categoryService($params);
        }
        
        $yahoo_Api_Result = array(
            'faci_Name'=>$facility_name,
            'place'=>$place,
            'phone_num'=>$phone_num,
            'nearest_Sta'=>$nearest_Sta);
            
        return $yahoo_Api_Result;
    }
}
