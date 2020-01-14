<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Media;
use App\Tools\Weixins;

class Student extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        // $access_token=Weixins::getAccessToken();
        // echo $access_token;die;
        $data=Media::get()->toArray();
         return view("admin.student.index",['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
       return view('admin.student.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //  $imgages = $_FILES['images'];
        // dd($imgages);
        // 接值
      $data=$request->except('_token');
      //dd($data);
      //dd($postData);die;
         //文件上传
        $file = $request->images;//获取上传文件
       // dd($file);
        $ext=$file->getClientOriginalExtension();//得到文件后缀
       // echo $ext;die;
        $filename=md5(uniqid()).".".$ext;
       // echo $filename;die;
        $path=$request->images->storeAs('images',$filename);
       
//dd($path);
      // die;
        // if($request->hasFile('images')){  //移动上传文件
        //   $filePath=$file->store('images');//if判断有无文件
        //  }
          // 1.获取access_token
         //调接口
          $access_token=Weixins::getAccessToken();
         // 2.$url = 'http://上传地址＋access_token';
            $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=".$data['media_format'];
         // 3.合成图片内容地址
         $img = public_path().'/'.$path;
         //$img = 'C:/6eece550b68a8c3f3db171cfef085f2.jpg';
         // dd($img);
         // 4.$img = new \CURLFile($img);
        $img = new \CURLFile($img);
            // $postData=['medie'=>$filePath];
            // curlPost($url,$postData);
         // 5.$imgData = Weixins::lhPost($url, $img);
        $postData = ['media'=>$img];
         $imgData = Weixins::lhPost($url, $postData);
          $imgData = json_decode($imgData, true);//将图片路径转换为json
           $media_id=$imgData['media_id'];//微信返回的素材id
         // 6.$imgData = json_decode($imgData, true);
         //入库
          // if(isset($imgData['media_id'])){
                Media::create([
                    'media_name'=>$data['media_name'],
                    'media_format'=>$data['media_format'],
                    'media_type'=>$data['media_type'],
                    'media_url'=>$path,//素材上传地址
                    'weixins_media_id'=>$media_id,
                    'add_time'=>time(),

                ]);
         // }

       //  dd($imgData);
         // 7.$arr['media_id'] = $imgData->['media_id']
        // 8.引入model::create($arr);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    function aaa(){
        // $access_token="29_SvBDw-Wyoiod2mtfKa9sM5Ciz9TmzgmJzcFXhV13Q6iib-xyb6Mjc_UN5UawdvEBM0J7X_HGSH4spYa7it2BBUYfGbnlTAMrkJYiv1OvPDu7Odc5d8VSc-txwM8SGViAFAFTI";
         $access_token=Weixins::getAccessToken();
        // echo $access_token;die;
        $url= "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
       //echo $url;die;
        //参数
        $postData='{"expire_seconds": 604800, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "1907"}}}';
        //var_dump($postData);die;
        $res=Weixins::lhPost($url,$postData);
        var_dump($res);die;
    }
}
