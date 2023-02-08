<?php

namespace App\Repositories\Interfaces;

interface ForecastsRepositoryInterface
{
    public function getFirstForecastById();
    
    public function truncateForecast();
    
    public function deleteForecastById();
    
    public function decrementForecastById();
    
    public function getForecast();
    
}