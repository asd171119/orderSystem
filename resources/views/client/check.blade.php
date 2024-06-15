@extends('client/common')

@section('js')
	<script src="/js/client/check.js"></script>
@endsection

@section('css')
	<link rel="stylesheet" href="/css/client/check.css">
@endsection

@section('content')
	<div class="row m-0 justify-content-center bg">
		<h2 class="col-12 my-5 d-flex align-items-end justify-content-center font-weight-bold">確認訂單</h2>
		<div class="col-10 row p-4 detail align-items-start">
			<div class="col-12 p-0">
				<label class="col-12 p-0 pb-3 text-center timestamp">訂單時間：{{ $timestamp }}</label>
				<div class="col-12 p-0 m-0 row">
					@foreach($orders as $menuID => $order)
						<label class="col-7">{{ $order['MenuName'] }}</label>
						<label class="col-5 p-0">{{ $order['Amount'] }} 份 * {{ $order['Price'] }} 元</label>
					@endforeach
				</div>
			</div>
			<label class="col-12 p-0 pt-3 mt-auto text-right total">確認訂單：{{ $total }} 元</label>
		</div>
		<div class="col-5 mt-2 btn btn-white" onclick="javascript:save_order();">結帳</div>
	</div>

	<a class="text-white back-btn" href="javascript:goback();">
		<i class="fa-solid fa-arrow-left"></i>
	</a>
@endsection
