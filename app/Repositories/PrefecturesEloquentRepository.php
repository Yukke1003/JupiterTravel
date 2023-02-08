<?php

namespace App\Repositories;

use App\Models\Prefecture;

class PrefecturesEloquentRepository
{
    public function getPrefectureAll()
    {
        return Prefecture::all();
    }
    
    public function getPrefectureById($id)
    {
        return Prefecture::where('prefecture_id','=',$id)->get()->all();
    }
    
    public function booleanPrefectureIstrue()
    {
        return Prefecture::where('is_weather',true)->get()->all();
    }
    
}
