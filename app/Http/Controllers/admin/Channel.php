<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Weixins;
use App\Model\Channel as Chan;


class Channel extends Controller
{
    public function create(){
    	return view('admin.channel.create');
    }
    public function store(Request $request){
    	$data=request()->except('_token');
    	//dd($data['channel_name']);
    	 // 1.获取access_token
         //调用接口
          $access_token=Weixins::getAccessToken();
          //dd($access_token);
         //地址
          $url= "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
          //参数
        	// $postData='{"expire_seconds": 259200, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$data['channel_sign'].'"}}}';
        	//dd($postData);
        						//从数组中取出一个值
        	$postData=[
        		'expire_seconds'=>259200,
        		'action_name'=>'QR_STR_SCENE',
        		'action_info'=>[
        				'scene'=>[
        				'scene_str'=>$data['channel_sign']
        			]
        		]
        	];
        	$postData=json_encode($postData);
        	//dd($postData);
         //发送请求
         	$res=Weixins::lhPost($url,$postData);
         	//向封装中发送请求并返回一条数据
        	$res=json_decode($res,true);
        	//将这条数据转化成数组
        	//dd($res);
        	////获取二维码图片参数
        	$ticket=$res['ticket'];

        	Chan::create([
        			'channel_name'=>$data['channel_name'],
        			'channel_sign'=>$data['channel_sign'],
        			'ticket'=>$ticket,

        	]);

    		//dd($ticket);
    }
    public function index(){
    	$data=Chan::get()->toArray();
    	return view('admin.channel.index',['data'=>$data]);
    }
    public function aph(){
        $data=Chan::get()->toArray();
        $xStr="";
        $yStr="";
        foreach($data as $key => $value){
            $xStr.='"'.$value['channel_name'].'",';
            $yStr.=$value['num'].',';
        }
        $xStr=rtrim($xStr,",");//去除右侧结尾,
        $yStr=rtrim($yStr,",");
        return view('admin.channel.aph',[
            'xStr'=>$xStr,
            'yStr'=>$yStr
        ]);
    }
    public function weather(){
        return view('admin.channel.weather');
    }
    public function getweather(Request $request){
       //接城市名
       $city = $request->input("city");
       //调用天气接口获取一周天气数据
       $url="http://api.k780.com:88/?app=weather.future&weaid=".$city."&&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json";
       $data=file_get_contents($url);
       return $data;
    }

}
