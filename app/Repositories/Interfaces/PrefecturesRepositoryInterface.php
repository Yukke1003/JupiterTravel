<?php

namespace App\Repositories\Interfaces;

interface PrefecturesRepositoryInterface
{
    public function getPrefectureAll();
    
    public function getPrefectureById($id);
    
    public function booleanPrefectureIstrue();
}