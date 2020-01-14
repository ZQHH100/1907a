<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Newss as News;
use  App\Tools\Weixins;
use Illuminate\Support\Facades\Redis;
class Newss extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $new_title=request()->new_title;
        $where=[];
        if($new_title){
            $where[]=['new_title','like',"%$new_title%"];
        }
        $data=News::where($where)->paginate(2);
        $query=request()->all();
        return view('admin.newss.index',['data'=>$data,'query'=>$query]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.newss.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=request()->except('_token');
    
        $res=News::create([
            'new_title'=>$data['new_title'],
            'new_desc'=>$data['new_desc'],
            'new_author'=>$data['new_author'],
            'add_time'=>time()
        ]);
        if($res){
            echo "<script>alert('添加成功');location.href='/newss';</script>";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
            $data=Redis::get('show_'.$id);
        $data=unserialize($data);
        if(!$data){
            echo 'DB';
            $data =News::find($id);
            Redis::setex('show_'.$id,10,serialize($data));
        }
        
        Redis::setnx('num_'.$id,0);
        Redis::incr('num_'.$id);
        $num=Redis::get('num_'.$id);
        return view('admin.newss.show',['data'=>$data,'num'=>$num]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=News::find($id);

        return view('admin.newss.edit',['data'=>$data]);
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
         $data=request()->except('_token');
         //dd($data);
         $res=News::where('new_id',$id)->update($data);
          if($res){
            echo "<script>alert('修改成功');location.href='/newss';</script>";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     $res=News::destroy($id);
      if($res){
            echo "<script>alert('删除成功');location.href='/newss';</script>";
        }
    }
}
