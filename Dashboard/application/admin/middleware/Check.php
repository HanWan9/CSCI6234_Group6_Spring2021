<?php

namespace app\admin\middleware;

use think\facade\Session;

class Check
{
    public function handle($request, \Closure $next)
    {
         if(!Session::has('userId'))
         {
             if($request->isPost()) {
                 returnAjax("请登录后再进行操作！", false);
             } else {
                 return redirect('/login');
             }
         }
        return $next($request);
    }
}
