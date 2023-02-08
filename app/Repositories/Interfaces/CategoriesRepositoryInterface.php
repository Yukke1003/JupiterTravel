<?php

namespace App\Repositories\Interfaces;

interface ForecastsRepositoryInterface
{
    public function getCategoryAll();
    
    public function getCategoryById($id);
}