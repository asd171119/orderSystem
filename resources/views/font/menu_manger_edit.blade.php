@extends('index')

@section('js')
	<script src="/js/font/menu_manger_edit.js"></script>
@endsection

@section('css')
	<link rel="stylesheet" href="/css/font/menu.css">
@endsection

@section('content')
	<div class="col-12 d-flex justify-content-center edit-menu-bg">
		<div class="col-8 row my-4 justify-content-center edit-menu-card">

			<div class="col-12 align-items-center justify-content-between row p-0 title">
				<div class="col-4"></div>
				<lable class="col-4 mt-2 p-2 text-center">  新增 / 編輯菜單</lable>
				<div class="col-4 row">
					<div class="ml-auto btn" onclick="javascript:upload_image();"><i class="fa-lg fa-solid fa-plus"></i>  上傳圖片</div>
					<div class="ml-2 danger-btn {{ isset($menu->Image) && $menu->Image != '' ? '' : 'hidden' }}" onclick="javascript:delete_iamge('{{ $token }}');"><i class="fa-lg fa-solid fa-trash-can"></i>  刪除圖片</div>
				</div>
			</div>

			<form method="post" class="col-12 mt-4 justify-content-center row" enctype="multipart/form-data">
				{!! csrf_field() !!}
				<input type="hidden" name="Token" value="{{ $token }}">
				<input type="hidden" name="Image" value="{{ isset($menu->Image) ? $menu->Image : ''}}">
				<div class="col-12 d-flex justify-content-center align-items-center row">
					<img class="menu-image-preview" name="ImagePreview" src="{{ isset($menu->ImageSrc) ? $menu->ImageSrc : ''}}">
					<input class="hidden" name="Image" id="Image" type="file">
				</img>
				<div class="col-12 d-flex justify-content-center align-items-center mt-3">
					<label class="m-0 mr-2">名稱</label>
					<input name="Name" value="{{ isset($menu->Name) ? $menu->Name : '' }}">
				</div>
				<div class="col-12 d-flex justify-content-center align-items-center mt-3">
					<label class="m-0 mr-2">售價</label>
					<input type="number" name="Price" value="{{ isset($menu->Price) ? $menu->Price : '' }}">
				</div>
				<div class="col-12 d-flex justify-content-center align-items-center mt-3 hidden">
					<label class="m-0 mr-2">特價</label>
					<input type="number" name="OnSale" value="{{ isset($menu->OnSale) ? $menu->OnSale : '' }}" placeholder="若無特價請填 0">
				</div>
				<div class="col-12 d-flex justify-content-center align-items-top mt-3">
					<label class="m-0 mt-2 mr-2">狀態</label>
					<input type="hidden" name="Status" value="{{ isset($menu->Status) ? $menu->Status : '' }}">

					<input class="menu-satus-switch-checkbox hidden" type="checkbox" id="Status" {{ isset($menu->Status) && $menu->Status == 1 ? 'checked' : '' }}>
					<label class="menu-satus-switch" for="Status"></label>

				</div>
				<button type="submit" class="btn ml-auto mb-2 btn">確認</button>
			</form>
		</div>
	</div>

	@include('/common/modal')

@endsection
