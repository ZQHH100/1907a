<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Tools\Weixins;

class ImgController extends Controller
{
    public function img()
    {
    	$access_token = Weixins::getAccessToken();
    	$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=image";
    	$img = '/data/wwwroot/default/1907a/public/img1.jpg';
    	$img = new \CURLFile($img);
    			  //将图片转换为文件格式，因为图片无法上传，只能转换成文件
    	$postData['media'] = $img;//将图片放入数组
    	$returnImg = Weixins::lhPost($url, $postData);//进行解析
    	dd($returnImg);
    }

}
