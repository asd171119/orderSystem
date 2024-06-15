<?php use App\Constant\Constant; ?>
@extends('client/common')

@section('js')
	<script src="/js/client/index.js"></script>
@endsection

@section('css')
	<link rel="stylesheet" href="/css/client/index.css">
@endsection

@section('content')
	<div>
		<div class="col-12 d-flex align-items-center header">
			<h3 class="col-12 m-0 text-white text-center font-weight-light">Restaurant</h3>
		</div>

		<div class="col-12 d-flex justify-content-center py-3">
			<div class="slider-btns">
				<span id="pre-btn"><i class="fa-solid fa-chevron-left"></i></span>
				<span id="next-btn"><i class="fa-solid fa-angle-right"></i></span>
			</div>
			<div class="slider-wrapper">
				<div class="dots"></div>
				<div class="slides">
					<img src="/images/common/home_1.jpeg" alt="nature">
				</div>
				<div class="slides">
					<img src="/images/common/home_2.jpeg" alt="nature">
				</div>
				<div class="slides">
					<img src="/images/common/home_3.jpeg" alt="nature">
				</div>
				<div class="slides">
					<img src="/images/common/home_4.jpeg" alt="nature">
				</div>
			</div>
		</div>
		<div class="col row m-0 mt-3 text-center text-white">
			<div class="col-12 row m-0 mb-4 justify-content-around">
				<div class="col-5 m-0 row justify-content-center align-items-center index-btn" onclick="javascript:goto_verify('{{ constant::ORDER_TYPE_TAKEAWAY }}')">
					<i class="fa-solid fa-bag-shopping fa-5x"></i>
					<h5 class="m-0 font-weight-bold">外帶</h5>
				</div>
				<div class="col-5 m-0 row justify-content-center align-items-center index-btn" onclick="javascript:goto_verify('{{ constant::ORDER_TYPE_INTERNAL }}')">
					<i class="fa-solid fa-bowl-food fa-4x"></i>
					<h5 class="col-12 m-0 font-weight-bold">內用</h5>
				</div>
			</div>
			<div class="col-12 row m-0 justify-content-center">
				<div class="col-11 row justify-content-center align-items-center index-btn lg-btn" onclick="javascript:goto_menu();">
					<i class="fa-solid fa-clipboard-list fa-4x"></i>
					<h4 class="ml-3 m-0 font-weight-bold">瀏覽菜單</h4>
				</div>
			</div>
		</div>
	</div>
	<footer class="col-12 mt-auto row m-0">
		<label class="col-12 m-0 text-center">用餐時間為 2 小時</label>
		<label class="col-12 m-0 text-center">Dining time is limited to 2 hours.</label>
	</footer>
@endsection