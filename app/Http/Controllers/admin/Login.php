<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Admins;

class Login extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logindo()
    {
        $post=request()->except('_token');
       // dd($post);
          $where = [
            ['account','=',$post['account']],
            ['password','=',$post['password']]
       ];
        $user=\DB::table('admins')->where($where)->first();
         if($user){
            session(['user'=>$user]);
                request()->session()->save();
                    return redirect('/weixin')->with('msg','登录成功');
         }
    }


}
