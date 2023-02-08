<?php

namespace App\Repositories;

use App\Models\Category;

class categoriesEloquentRepository
{
    public function getCategoryAll()
    {
        return Category::all();
    }
    
    public function getCategoryById($id)
    {
        return Category::where('category_id','=',$id)->get()->all();
    }
    
}
