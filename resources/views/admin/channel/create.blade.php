@extends('admin/layouts/admin')

@section('title', 'dyd')

@section('content')


<body>
<h1>素材管理</h1>
<form action="{{url('/channel/store')}}" method="post" enctype="multipart/form-data">
	@csrf
  <div class="form-group">
    <label for="exampleInputEmail1">渠道名称:</label>
    <input type="text"  name="channel_name" placeholder="请填写渠道名称">
  </div>
    <div class="form-group">
    <label for="exampleInputEmail1">渠道标识:</label>
    <input type="text"  name="channel_sign" placeholder="请填写渠道标识">
  </div>


  <button type="submit" class="btn btn-default">提交</button>
</form>
</body>


@endsection