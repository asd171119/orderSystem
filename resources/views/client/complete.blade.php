@extends('client/common')

@section('js')

@endsection

@section('css')
	<link rel="stylesheet" href="/css/client/complete.css">
@endsection

@section('content')
	<div class="row m-0 justify-content-center bg">
		<div class="col-12 p-0 m-0">
			<div class="col-12 p-0 d-flex justify-content-center align-items-center icon">
				<i class="mt-5 text-white fa-regular {{ $status == true ? 'fa-circle-check' : 'fa-circle-xmark' }} fa-7x"></i>
			</div>
			<div class="col-12 p-0 result-area">
				<h1 class="col-12 py-4 text-center font-weight-bold result">{{ $status == true ? '訂單成功！' : '訂單失敗！' }}</h1>
			</div>
			<div class="col-12 p-0 text-center font-weight-bold msg">
				{{ $msg }}
			</div>
		</div>
	</div>

	<a class="text-white back-btn" href="/client/">
		<i class="fa-solid fa-arrow-left"></i>
	</a>
@endsection
