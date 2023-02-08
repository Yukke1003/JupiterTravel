<?php
namespace App\Services\Traits;

trait ExecuteAPIService
{
    //各種Web Apiの実行(open weather map api/yahoo Lacal Api)
    protected function execute_Api($url,$params)
    {
        $query = http_build_query($params);
        $search_url = $url.'?'.$query;
            
        return file_get_contents($search_url);
    }
    
}
