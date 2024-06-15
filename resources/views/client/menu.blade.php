@extends('client/common')

@section('js')

@endsection

@section('css')
	<link rel="stylesheet" href="/css/client/menu.css">
@endsection

@section('content')
	<div>
		<div class="col-12 d-flex align-items-center header">
			<h3 class="col-12 m-0 text-white text-center font-weight-light">菜單</h3>
		</div>
		<div class="col-12 row m-0 justify-content-center menu-wrap">
			@foreach($menus as $key => $menu)
				<div class="col-12 col-lg-3 text-center my-1 menu-border">
					<div class="col-12">
						<img class="menu-img" src="{{ $menu['ImageSrc'] }}">
					</div>
					<label class="col-12">{{ $menu['Name'] }}</label>
					<label class="col-12">{{ $menu['Price'] }} 元</label>
				</div>
			@endforeach
		</div>
	</div>

	<a class="text-white back-btn" href="/client/">
		<i class="fa-solid fa-arrow-left"></i>
	</a>
@endsection