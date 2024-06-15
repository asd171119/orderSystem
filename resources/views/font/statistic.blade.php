
@extends('index')

@section('js')
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script src="/js/font/statistic.js"></script>
@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link rel="stylesheet" href="/css/font/statistic.css">
@endsection

@section('content')

	<div class="col-12 row m-0 mt-3">
		<h3 class="col-12 text-center m-0 mb-3 pb-3 border-b">{{ $range }}</h3>

		<div class="col-12 d-flex align-items-center pb-2">
			<label class="col-2 text-right m-0 ml-auto">查詢時間：</label>
			<input id="range" name="range" value="">
			<div class="btn ml-3 py-1" onclick="javascript:statistic();">查詢</div>
		</div>

		<label class="col-12 text-center m-0 {{ empty($statistics) ? '' : 'hidden' }}">暫無統計數據</label>

		@foreach($statistics as $sMenuName => $statustic)
			<div class="col-12 row mt-3 justify-content-center align-items-center">
				<label class="col-2 text-right m-0">{{ $sMenuName }}</label>
				<div class="col-8 p-0 bar">
					<div class="{{ $statustic['percentage'] == 0 ? 'none' : ''}} statistic" style="width:{{ $statustic['percentage'] }}%;">
						{{ $statustic['percentage'] }} %
					</div>
				</div>
				<label class="m-0 col-1">數量 {{ $statustic['count'] }}</label>
			</div>
		@endforeach
	</div>

@endsection
