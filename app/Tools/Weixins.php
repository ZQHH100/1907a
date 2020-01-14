<?php

namespace App\Tools;
use Illuminate\Support\Facades\Cache;
//微信核心类
class Weixins
{
	const appId = "wxa6b3840740a4b135";
	const appSerect="d5338c1d3433874ccfe5ec0d548ce87d";
        //封装一个消息回复
	public static function zqhh($xmlObj,$msg){
	  echo "<xml>
	  <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
	  <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
	  <CreateTime>".time()."</CreateTime>
	  <MsgType><![CDATA[text]]></MsgType>
	  <Content><![CDATA[".$msg."]]></Content>
	</xml>";die;
	}
	public static function getAccessToken(){
		//先判断缓存有无数据
		$access_token=Cache::get('access_token');
		//有数据返回
		if(empty($access_token)){
			//获取access_token（微信接口调用凭证）
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".Self::appId."&secret=".Self::appSerect;
			// $data = file_get_contents($url);
			$data = self::lhPost($url);
			$data = json_decode($data,true);
			$access_token=$data['access_token'];//token如何存储两小时
			Cache::put('access_token',$access_token,7200);//120分钟
		}
		//没有数据再进去调用微信接口获取=》存入缓存
		return $access_token;
		  

	}
	//获取用户信息
	public static function getUserInfoByOpenId($openid){
		$access_token=Self::getAccessToken();
		$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
			$data = file_get_contents($url);
			$data = json_decode($data,true);
			return $data;
	}

	/* post封装*/
	public static function lhPost($url, $postData=''){
		$a = curl_init();
		curl_setopt($a, CURLOPT_URL, $url);
		curl_setopt($a, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($a, CURLOPT_POST, 1);
		curl_setopt($a, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($a, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($a, CURLOPT_SSL_VERIFYHOST, false);
		$b = curl_exec($a);
		curl_close($a);
		return $b;
	}
}
