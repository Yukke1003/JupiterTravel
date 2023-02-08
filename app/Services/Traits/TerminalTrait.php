<?php
namespace App\Services\Traits;

trait TerminalService
{
    //ユーザーの使用端末の検出
    protected function terminalService($user_agent)
    {
        if ((strpos($user_agent, 'iPhone') !== false)
            || (strpos($user_agent, 'iPod') !== false)
            || (strpos($user_agent, 'Android') !== false)) {
            $terminal ='mobile';
        } else {
            $terminal = 'pc';
        }
        
        return $terminal;
    }
    
}
