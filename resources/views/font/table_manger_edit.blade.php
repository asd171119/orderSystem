@extends('index')

@section('js')

@endsection

@section('css')
	<link rel="stylesheet" href="/css/font/table.css">
@endsection

@section('content')
	<div class="col-12 d-flex justify-content-center edit-table-bg">
		<div class="col-8 row my-5 justify-content-center edit-table-card">
			<lable class="col-12 mt-2 p-2 text-center font-weight-bold">新增 / 編輯桌次</lable>
			<hr class="my-1"/>
			<form method="post" class="col-12 mt-4 row">
				{!! csrf_field() !!}
				<input type="hidden" name="Token" value="{{ isset($table) ? $table->Token : '' }}">
				<div class="col-12 d-flex justify-content-center align-items-center">
					<label class="m-0 mr-2">名稱</label>
					<input name="Name" value="{{ isset($table) ? $table->Name : '' }}">
				</div>
				<div class="col-12 d-flex justify-content-center align-items-center mt-3">
					<label class="m-0 mr-2">人數</label>
					<input type="number" name="People" value="{{ isset($table) ? $table->People : '' }}">
				</div>
				<button type="submit" class="btn ml-auto mt-5 btn">確認</button>
			</form>
		</div>
	</div>

@endsection
