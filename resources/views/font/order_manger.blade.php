<?php use App\Constant\Constant; ?>

@extends('index')

@section('js')
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="/js/font/order_manger.js"></script>
@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="/css/font/order.css">
@endsection

@section('content')
	<script>var orderDatas = @json($orderDatas); </script>
	<input type="hidden" class="params" name="REFRESH_DURATION" value="<?php echo constant::REFRESH_DURATION; ?>">
	<input type="hidden" class="params" name="ORDER_DETAIL_STATUS_CANCEL" value="<?php echo constant::ORDER_DETAIL_STATUS_CANCEL; ?>">

	<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

	<div class="col-12 row m-0">
		@foreach($tableStatus as $nKey => $table)
			<div class="col-3 row m-0 mt-5 px-4 justify-content-center">
				<div class="col-12 pb-3 row m-0 {{ $table['Status'] == constant::TABLE_STATUS_SERVE ? 'reserve-card' : 'table-card' }}">
					<div class="col-12 mt-3 p-0 d-flex justify-content-between">
						<label class="p-1 table-card-title">{{ $table['TableName'] }}</label>
						<label class="table-card-font">{{ $table['StatusStr'] }}</label>
					</div>
					<div class="col-12 mt-3 p-0 row d-flex justify-content-between">
						<label class="col-12 table-card-font">桌次人數：{{ $table['TablePeople'] }}</label>
						<label class="col-12 pr-0 table-card-font {{ $table['Status'] == constant::TABLE_STATUS_NONE ? 'hidden' : '' }}">
							訂單編號：{{ $table['OrderID'] }}
						</label>
						<label class="col-12 pr-0 table-card-font {{ $table['Status'] == constant::TABLE_STATUS_NONE ? 'hidden' : '' }}">
							聯絡資訊：{{ $table['Phone'] }}
						</label>
						<label class="col-12 pr-0 table-card-font {{ $table['Reserve'] == constant::DEFAULT_DATETIME ? 'hidden' : '' }}">
							訂位時間：{{ $table['ReserveStr'] }}
						</label>
					</div>
				</div>
				<div class="mt-2 secondary-btn {{ $table['Status'] == constant::TABLE_STATUS_USED ? '' : 'hidden' }}" onclick="javascript:finish({{ $table['id']}});">恢復空桌</div>
				<div class="mt-2 btn {{ $table['Status'] == constant::TABLE_STATUS_NONE ? '' : 'hidden' }}" onclick="javascript:reserve({{ $table['id']}});">訂位</div>
				<div class="mt-2 secondary-btn {{ $table['Status'] == constant::TABLE_STATUS_BOOK || $table['Status'] == constant::TABLE_STATUS_SERVE ? '' : 'hidden' }}" onclick="javascript:cancel_reserve({{ $table['id']}});">取消訂位</div>

				<div class="mt-2 ml-2 btn {{ $table['Status'] == constant::TABLE_STATUS_USED ? '' : 'hidden' }}" onclick="javascript:show_orderDetail('{{ $table['OrderID'] }}');">訂單資訊</div>
				<?php
					$hidden = '';
					if($table['Status'] == constant::TABLE_STATUS_USED)
					{
						$hidden = $orderDatas[$table['OrderID']]['Status'] == constant::ORDER_STATUS_PAY ? 'hidden' : '';
					}
					else
					{
						$hidden = 'hidden';
					}
				?>
				<div class="mt-2 ml-2 danger-btn {{ $hidden }}" onclick="javascript:paid_order('{{ $table['OrderID'] }}');">訂單結帳</div>
			</div>
		@endforeach
	</div>

	<div class="col-12 row m-0">
		@foreach($takeawayDatas as $sOrderID => $data)
			<div class="col-3 row m-0 mt-5 px-4 justify-content-center">
				<div class="col-12 pb-3 row m-0 table-card">
					<div class="col-12 mt-3 p-0 d-flex justify-content-between">
						<label class="p-1 table-card-title">外帶</label>
						<label class="table-card-font">{{ $data['StatusStr'] }}</label>
					</div>
					<div class="col-12 mt-3 p-0 row d-flex justify-content-between">
						<label class="col-12 pr-0 table-card-font">
							訂單編號：{{ $sOrderID }}
						</label>
					</div>
				</div>
				
				<div class="mt-2 ml-2 btn" onclick="javascript:show_orderDetail('{{ $sOrderID }}');">訂單資訊</div>
				<div class="mt-2 ml-2 danger-btn" onclick="javascript:paid_order('{{ $sOrderID }}');">訂單結帳</div>
			</div>
		@endforeach
	</div>

	@include('/common/modal')

@endsection
