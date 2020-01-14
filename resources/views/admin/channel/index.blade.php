@extends('admin/layouts/admin')

@section('title', 'dyd')

@section('content')
<table class='table table-hover table-bordered'>
		<tr>
			<td>渠道id</td>
			<td>渠道名称</td>
			<td>渠道标识</td>
			<td>渠道二维码</td>
			<td>关注人数</td>
		</tr>
@foreach($data as $k=>$v)
		<tr>
			<td>{{$v['channel_id']}}</td>
			<td>{{$v['channel_name']}}</td>
			<td>{{$v['channel_sign']}}</td>
			<td>
				<img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={{$v['ticket']}}" width="100">
			</td>
			<td>{{$v['num']}}</td>
		</tr>
@endforeach
</table>










@endsection