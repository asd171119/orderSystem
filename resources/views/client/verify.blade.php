<?php use App\Constant\Constant; ?>
@extends('client/common')

@section('js')
	<!-- <script src="/js/client/index.js"></script> -->
@endsection

@section('css')
	<link rel="stylesheet" href="/css/client/verify.css">
@endsection

@section('content')
	<div class="col-12 p-0 full-page">
		<div class="col-12 row m-0 p-0 full-page">
			<div class="col-12 p-0 m-0 row justify-content-center align-items-center bg bg-up">
				<div class="col-12 m-0 row text-white justify-content-center pt-4">
					<i class="fa-solid fa-user fa-5x"></i>
					<label class="col-12 mt-2 text-center font-weight-bold">顧客資訊</label>
				</div>
			</div>
			<div class="col-12 p-0 bg"></div>
		</div>
		<div class="col-12 row m-0 p-0 justify-content-center align-items-center param-area">
			<form method="post" class="col-12 m-0 pt-5 row justify-content-center param-wrap">
				{!! csrf_field() !!}
				@if(isset($Status) && $Status == 'table')
					<div class="col-8 py-5 row justify-content-between param">
						@foreach($Tables as $index => $Table)
							<div class="col-6 d-flex justify-content-center">
								<input type="radio" name="table" id="{{ $Table['id'] }}" value="{{ $Table['id'] }}">
								<label class="ml-1 my-1" for="{{ $Table['id'] }}">{{ $Table['Name']}}</label>
							</div>
						@endforeach
					</div>
				@else
					<div class="col-8 py-5 row justify-content-center param">
						<input class="text-center my-5 phone-input" type="text" name="phone" placeholder="請輸入電話號碼" autocomplete="off">
					</div>
				@endif
				<input type="hidden" name="verifyType" value="{{ isset($Status) ? $Status : 'phone'}}">
				<button type="submit" class="col-10 mt-5 btn">確認</button>
			</form>
		</div>
	</div>

	<a class="text-white back-btn" href="/client/">
		<i class="fa-solid fa-arrow-left"></i>
	</a>
@endsection
