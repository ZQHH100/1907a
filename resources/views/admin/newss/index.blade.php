<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
<form action="" method="">
		<input type="text" name="new_title" value="{{$query['new_title']??''}}" placeholder="请输入标题名">
		<button>搜索</button>
	</form>

		<table>
			<tr>
				<td>标题</td>
				<td>内容</td>
				<td>作者</td>
				<td>时间</td>
				
				<td>操作</td>
			</tr>
	@foreach($data as $v)
			<tr>
				<td>{{$v->new_title}}</td>
				<td>{{$v->new_desc}}</td>
				<td>{{$v->new_author}}</td>
				<td>{{date("Y-m-d H:i:s",$v->add_time)}}</td>
			
				<td>
					<a href="{{url('/newss/delete/'.$v->new_id)}}"  class="btn btn-danger">删除</a>
					<a href="{{url('/newss/edit/'.$v->new_id)}}"  class="btn btn-danger">修改</a>
					<a href="{{url('/newss/show/'.$v->new_id)}}"  class="btn btn-danger">详情</a>
				</td>
			</tr>
	@endforeach
		</table>
		{{$data->appends($query)->links()}}

</body>
</html>