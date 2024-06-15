<?php use App\Constant\Constant; ?>
@extends('index')

@section('js')
	<script src="/js/back/order.js"></script>
@endsection

@section('css')
	<link rel="stylesheet" href="/css/font/order.css">
@endsection

@section('content')
	<input type="hidden" class="params" name="REFRESH_DURATION" value="<?php echo constant::REFRESH_DURATION; ?>">

	<div class="col-12 row m-0 mt-3">
		@foreach($orderDatas as $orderID => $orderData)
			<div class="col-3 px-4 row justify-content-center text-white font-weight-bold">
				<div class="col-12 row justify-content-center table-card">
					<label class="col-12 m-0 px-0 py-2 mt-3 text-center order-title">訂單編號：{{ $orderID }}</label>
					<div class="col-12 pt-2 pb-3">
						@foreach($orderData as $index => $data)
						<div class="col-12 pt-2 pb-1 d-flex justify-content-between order-detail" onclick="javascript:finish_orderDetail('{{ $data['id'] }}');">
							<div class="">{{ $data['Name'] }}</div>
							<div class="">{{ $data['Amount'] }} 份</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		@endforeach
	</div>

@endsection
