@extends('admin/layouts/admin')

@section('title', 'dyd')

@section('content')


<body>
<h1>素材管理</h1>
<form action="{{url('/student/store')}}" method="post" enctype="multipart/form-data">
	@csrf
  <div class="form-group">
    <label for="exampleInputEmail1">素材名称:</label>
    <input type="text"  name="media_name" placeholder="请填写素材名称">
  </div>
   
   <div class="form-group">
    <label for="exampleInputEmail1">素材文件:</label>
    <input type="file"  name="images" >
  </div>
  <div class="form-group">
		<label for="exampleInputEmail1">素材类型</label>
			<select name="media_type">
			<option value="1">临时</option>
			<option value="2">永久</option>
			</select>
	</div>
	  <div class="form-group">
		<label for="exampleInputEmail1">格式</label>
			<select name="media_format">
			<option value="image">图片</option>
			<option value="video">视频</option>
			<option value="voice">音频</option>
			</select>
	</div>

  <button type="submit" class="btn btn-default">提交</button>
</form>
</body>


@endsection