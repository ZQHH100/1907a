<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Weixins;
use App\Model\Newss;
use App\Model\Client;
use DB;
class Weixin extends Controller
{
	private $student=['刘赤赤','史帅波','施佳奇','胡彩笼'];


    public function index(Request $request){
    	//echo '1';
    	//微信接入
   		//$echostr = $request->input('echostr');die;
    	$xml =file_get_contents('php://input');
		//写进文件
		file_put_contents('dayede2.txt',"\n".$xml."\n",FILE_APPEND);
		$xmlObj = simplexml_load_string($xml);
		  
		//如果是关注
		if($xmlObj->MsgType=='event'&& $xmlObj->Event=='subscribe'){
			//关注时 获取用户基本信息
			$data=Weixins::getUserInfoByOpenId($xmlObj->FromUserName);
			//dd($data);
			//关注时 获取用户基本信息
			//$eventKey=$xmlObj->EventKey;//截取字符串qrscene_111
			//得到渠道标识
			$channel_sign=$data['qr_scene_str'];			
			//dd($channel_sign);
			//根据渠道标识，关注人数递增
			\DB::table('channel')->where(['channel_sign'=>$channel_sign])->increment('num');
			//存入用户基本信息
			//判断用户基本信息表内有没有数据  （通过opendid查询表）
			$userInfo=Client::where('openid',$xmlObj->FromUserName)->first();
			if($userInfo){
				Client::where('openid',$xmlObj->FromUserName)->update([
					'subscribe_time'=>$data['subscribe_time'],
					'is_del'=>1
				]);
			}else{
				Client::insert([
					'openid'=>$data['openid'],
					'nickname'=>$data['nickname'],
					'sex'=>$data['sex'],
					'subscribe_time'=>$data['subscribe_time'],
					'remark'=>$data['remark'],
					'groupid'=>$data['groupid'],
					'qr_scene_str'=>$data['qr_scene_str']
					
				]);
			}
				
			
			
			
			// $eventKey=$xmlObj->EventKey;

			$nickname=$data['nickname'];//取到用户昵称

			if($data['sex']==1){
				$sex='先生';
			}elseif($data['sex']==2){
				$sex='女士';
			}elseif($data['sex']==0){
				$sex='客户';
			}
			$msg="欢迎".$nickname.$sex."关注";
		  //回复文本消息
		  Weixins::zqhh($xmlObj,$msg);
		}
	//取消关注时
		if($xmlObj->MsgType=='event'&& $xmlObj->Event=='unsubscribe'){
	//获取用户信息 并进行修改操作
		Client::where(['openid'=>$xmlObj->FromUserName])->update(['is_del'=>0]);
									//修改成功或失败只返回true或false
		$qr_scene_str=Client::where(['openid'=>$xmlObj->FromUserName])->value('qr_scene_str');//得到渠道标识
		//update client set is_del=1 where openid;
	
		
	//渠道表统计人数减一
		\DB::table('channel')->where('channel_sign', $qr_scene_str)->decrement('num');
		}
		//自定义菜单
		  $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.Weixins::getAccessToken();
	        $data = ' {
			     "button":[
			     {  
			          "type":"click",
			          "name":"今日歌曲",
			          "key":"V1001_TODAY_MUSIC"
			      },
			      {
			           "name":"菜单",
			           "sub_button":[
			           {    
			               "type":"view",
			               "name":"搜索",
			               "url":"http://www.soso.com/"
			            },
			            {
			               "type":"click",
			               "name":"赞一下我们",
			               "key":"V1001_GOOD"
			            }]
			       }]
				 }';
		 $res = Weixins::lhPost($url, $data);



		//用户发送来文本消息
		if($xmlObj->MsgType=='text'){
		  //得到用户的发送内容
		  $content=trim($xmlObj->Content);
		 
		  if($content =='1'){
		      //进行回复
		      //回复本班全部
		      $msg=implode(",",$this->student);
		          //将数组转化为字符串
		          
		     Weixins::zqhh($xmlObj,$msg);
		  }elseif($content=='2'){
		     //进行回复
		     //随机回复一个同学姓名
		     shuffle($this->student);
		     $msg=$this->student[0];
		     Weixins::zqhh($xmlObj,$msg);

		  }elseif(mb_strpos($content,"天气") !== false){
		     //进行回复天气
		     $city = rtrim($content,"天气");
		      if(empty($city)){
		        $city="北京";
		      }
		      $url="http://api.k780.com:88/?app=weather.future&weaid=".$city."&&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json";
		      $data =file_get_contents($url);
		      $data=json_decode($data,true);
		      $msg="";
		      if(empty($data['result'])){
		        zqhh($xmlObj,'请输入正确的地区');
		      }
		      foreach($data['result'] as $key=>$value){
		        $msg .= $value['days']." ".$value['week']." ".$value['citynm']." ".$value['temperature']."\n";
		      }
		     Weixins::zqhh($xmlObj,$msg);//回复所在地区的消息
		  }elseif($content=='最新新闻'){
		  		$data=Newss::orderBy('new_id','desc')->limit(1)->first()->toArray();//查询数据库里最新添加的一条数据
		 		//dd($data);
		  		//$msg=toArr($data,true);
		  		 Weixins::zqhh($xmlObj,$data['new_desc']);//回复最新添加的一条新闻内容
		  }elseif(mb_strpos($content,"新闻")!==false){//判断新闻首次出现的位置，不能为空

		  		$keword=mb_substr($content,2);//截取两位字符获取之后的标题数据

		  		
		  		$where=[];
		  		$where []= ['new_title','like',"%$keword%"];//查询

		  		$res=Newss::Where($where)->first();//获取首条
		  		// dd($res);
		  		if($res){
		  		$str=$res->new_desc;
		  			 Weixins::zqhh($xmlObj,$str);//回复 
		  		}else{
		  			 Weixins::zqhh($xmlObj,"暂无相关新闻");
		  		}
		  }
	   }
    }
    		public function sendAllOpenId(){
    			$openid_list=[
    				 "oisZkwag5dyYS1E5c_tgXoQKz_VU",
   					 "oisZkwZBWqhlQT79YQdWEtWMPLko",
    			];
    			$msg="记忆就像镜子,映照出自己内心的模样,
					  也许还想忘记,也许还是害怕,
					  面对自己的心声,却没有选择坦白.
					  我的灵魂支离破碎,再拼凑不出自己
					  再睁开眼,现世已是荒芜";
    			$postData=[
    				  "touser" => $openid_list,
    				  "msgtype"=> "text",
    				  "text" => [
    				  		"content" =>$msg
    				  ]
    			];
    			// echo print_r($postData);
    			// echo json_encode($postData);
    			$url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".Weixins::getAccessToken();
    			//$postData=json_encode($json_data,JSON_UNESCAPED_UNICODE);
    			//$res=Weixins::lhPost($url, $postData);

    			$response = Weixins::lhPost($url, json_encode($postData,JSON_UNESCAPED_UNICODE));
    			echo print_r($response);
    		}
    		public function test(){
    			$appid = env('WX_APPID');
    			$redirect_uri=urlencode(env('WX_AUTH_REDIRECT_URI'));
    			$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
    			echo $url;
    		}
    		public function auth(){
    			//接收code
    			$code = $_GET['code'];
    			//换取access_token
    			$url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APPID').'&secret='.env('WX_APPSEC').'&code='.$code.'&grant_type=authorization_code';
    			$json_data=file_get_contents($url);
    			$arr=json_decode($json_data,true);
    			print_r($arr);

    			//获取用户信息
    			$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
    			$json_user_info=file_get_contents($url);
    			$user_info_arr=json_decode($json_user_info,true);
    			print_r($user_info_arr);
    		}
}
