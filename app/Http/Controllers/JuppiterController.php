<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use DateTime;

use DateTimeZone;

use App\Prefecture;

use App\Category;

use App\Forecast;

use App\Services\WeatherDateService;

use App\Services\WeatherTimeService;

use App\Services\WeatherInsertService;

use App\Services\YahooLocalService;

use App\Services\TerminalService;

class JuppiterController extends Controller
{
    
    protected $date_service;
    
    protected $time_service;
    
    protected $insert_service;
    
    protected $category_service;
    
    protected $terminal_judge;
    
    protected $get_forecast;
    
    public function __construct(
        WeatherDateService $weatherDateService, 
        WeatherTimeService $weatherTimeService,
        WeatherInsertService $weatherInsertService,
        Forecast $forecast,
        YahooLocalService $yahooLocalService, 
        TerminalService $terminalService)
    {
        $this->date_service = $weatherDateService;
        $this->time_service = $weatherTimeService;
        $this->insert_service = $weatherInsertService;
        $this->category_service = $yahooLocalService;
        $this->terminal_judge = $terminalService;
        $this->get_forecast = $forecast;
    }
    
    
    public function index()
    {
        //TOP画面(自由検索)
        //都道府県を全て取得
        $prefectures = Prefecture::all();
        $prefect_form = array('選択してください');
        //都道府県の日本語名のみ取得
        foreach($prefectures as $prefect){
            array_push($prefect_form, $prefect->prefectJP);
        }
        //カテゴリーを全て取得
        $categories = Category::all();
        $category_form = array('選択してください');
        //カテゴリーの名前を取得
        foreach($categories as $cate){
            array_push($category_form, $cate->contents);
        }
        
        //自由検索画面でそれらを表示
        return view('main.index',[
            'prefect_form' => $prefect_form,
            'category_form' => $category_form
            ]);
    }

    public function simpleResult(Request $request)
    {
        //自由検索結果返却処理
        //ユーザー使用端末の判断
        $user_agent = $request->header('User-Agent');
        $terminal = $this->terminal_judge->terminalService($user_agent);
        
        //都道府県データの取得
        //modelの場合
        $target_prefect = Prefecture::where('prefecture_id','=',$request->prefecture)->get()->all();
        $target_prefect = array_shift($target_prefect);
        
        //sql文の場合
        //$prefectures = DB::select('select prefectEN,prefectJP from prefectures where prefecture_id=?',[$request->prefecture]);
        //$weaPre=$prefectures[0];
        
        //天気情報の取得
        $num = 0; //全件取得
        $weather_result = $this->date_service->weatherDateService($target_prefect,$num);
        
        //カテゴリーデータの取得
        $target_array = array();
        
        $target_categories = Category::where('category_id','=',$request->categories)->get()->all();
        
        if(isset($target_categories)){
            foreach($target_categories as $category){
                array_push($target_array,$category->YOLP_gc);
            }
        }else{
            $error_message  = 'カテゴリー選択できていません。必ず一つ以上選択してください。';
            return view(main.index,$error_message);
        }
        
        $target_category = implode(",",$target_array);
        $params = [
            'appid' => config('services.yahoo_local_api.yahoo_key'),
            'gc' => $target_category,
            'ac' => $target_prefect->JIS_ac,
            'output' => 'json',
            'sort' => 'hybrid',
            'results' => 10
            ];
        
        // 端末判断
        if($terminal==='mobile'){
            $params += array('device' => 'mobile') ;
        }
        
        //YahooLocalApiの情報の取得
        $yahoo_result = $this->category_service->categoryService($params);
        
        //それぞれのAPIの実行結果を結合
        $api_result = $weather_result + $yahoo_result;
        
        return view('main.result',$api_result);
    }
    
    public function planTopShow()
    {
        //TOP画面(旅行策定プラン)
        
        //全国の天気の取得
        //アクセス時点での時間の取得
        $weather_prefects = Prefecture::where('is_weather',true)->get()->all();
        
        $now = new DateTime();
        $now = $now->setTimezone(new DateTimeZone('Asia/Tokyo'));
        
        $weather_oldest = Forecast::orderBy('weather_date','asc')->first();
        
        if(!isset($weather_oldest)){
            $start_num = 0; //全件取得
            $this->insert_service->weatherInsert($weather_prefects,$start_num);
            
        }else{
            $compare = new DateTime($weather_oldest->weather_date);
            $date_diff = $compare->diff($now);
            $year = (int)$date_diff->format('%y');
            $day = (int)$date_diff->format('%d');
            
            if($date_diff->invert === 0){
                if(0<$year || 6<$day){
                    Forecast::truncate();
                    $start_num = 0; 
                    $this->insert_service->weatherInsert($weather_prefects,$start_num);
                    
                }else if(0<$day && $day<7){
                    $delete = array();
                    while(0<$day){
                        array_push($delete,$day);
                        $day--;
                    }
                    Forecast::whereIn('weather_id',$delete)->delete();
                    Forecast::decrement('weather_id',$day);
                    $start_num = 7 - $day; //部分取得
                    $this->insert_service->weatherInsert($weather_prefects,$start_num);

                }
            }
        }
        
        $forecast_result = $this->get_forecast->getForecast();
        //旅行プラン策定画面でそれらを表示(ページ要変更)
        return view('main.travel_top',$forecast_result);
    }
    
    public function planSetting()
    {
        //都道府県を全て取得
        $prefectures = Prefecture::all();
        $prefect_form = array('選択してください');
        //都道府県の日本語名のみ取得
        foreach($prefectures as $prefect){
            array_push($prefect_form, $prefect->prefectJP);
        }
        
        
    }
    
    
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     
}
