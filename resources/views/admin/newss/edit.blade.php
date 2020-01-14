<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="{{url('newss/update',$data->new_id)}}" method="post" >
		@csrf
		<table>
			<tr>
				<td>标题</td>
				<td><input type="text" value="{{$data->new_title}}" name="new_title"></td>
			</tr>
			<tr>
				<td>内容</td>
				<td>
					<textarea name="new_desc">{{$data->new_desc}}</textarea>
				</td>
			</tr>
			<tr>
				<td>作者</td>
				<td><input type="text" value="{{$data->new_author}}" name="new_author"></td>
			</tr>
			<tr>
				<td><input type="submit" value="添加"></td>
				<td></td>
			</tr>
		</table>
	</form>
</body>
</html>