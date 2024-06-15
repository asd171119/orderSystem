@extends('client/common')

@section('js')
	<script src="/js/client/order.js"></script>
@endsection

@section('css')
	<link rel="stylesheet" href="/css/client/order.css">
@endsection

@section('content')
	<div>
		<div class="col-12 d-flex align-items-center header">
			<h3 class="col-12 m-0 text-white text-center font-weight-light">選擇餐點</h3>
		</div>
		<form method="post" action="/client/check_order">
			{!! csrf_field() !!}
			<div class="col-12 m-0 p-0 row justify-content-center menu-wrap">
				@foreach($menus as $key => $menu)
					<div class="col-12 p-0 pl-1 pb-2 my-1 row justify-content-between menu-border">
						<div class="col-4">
							<img class="menu-img" src="{{ $menu['ImageSrc'] }}">
						</div>
						<div class="col-6 m-0 p-0 row">
							<label class="col-12 m-0 mt-2 p-0">{{ $menu['Name'] }}</label>
							<label class="col-12 m-0 p-0">{{ $menu['Price'] }} 元</label>
						</div>
						<div class="col-2 m-0 row justify-content-center text-center">
							<div class="font-weight-bold order-btn" onclick="javascript:order_btn('{{ $menu['id'] }}', '{{ $menu['Price'] }}', 'add');">+</div>
							<label class="m-0 my-1" id="amount-{{ $menu['id'] }}">0</label>
							<div class="font-weight-bold order-btn" onclick="javascript:order_btn('{{ $menu['id'] }}', '{{ $menu['Price'] }}', 'sub');">-</div>
						</div>
						<input type="hidden" name="menu-{{ $menu['id'] }}" id="menu-{{ $menu['id'] }}" value="0">
					</div>
				@endforeach
			</div>
			<div class="col-12 d-flex align-items-center justify-content-between footer">
				<label class="col-3 m-0 p-0 text-white text-right">總金額：</label>
				<label class="col-6 m-0 text-center order-price" id="total-price" data-total="0">$0</label>
				<button type="submit" class="btn-white" >確認</button>
			</div>
		</form>
	</div>

	<a class="text-white back-btn" href="/client/">
		<i class="fa-solid fa-arrow-left"></i>
	</a>
@endsection
