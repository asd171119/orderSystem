
@extends('index')

@section('js')
	<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="/js/font/menu_manger.js"></script>
@endsection

@section('css')
	<link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="/css/font/table.css">
	<link rel="stylesheet" href="/css/font/menu.css">
@endsection

@section('content')
	<div class="col-12">
		<table class="my-3" id="menus" width="50%">
			<thead>
				<tr>
					<th></th>
					<th><label class="px-3 th-label">名稱</label></th>
					<th><label class="px-3 th-label">價格</label></th>
					<th><label class="px-3 th-label">特價</label></th>
					<th><label class="px-3 th-label">狀態</label></th>
					<th>
						<div class="btn insert-btn" onclick="javascript:goto_menuMangerEdit();">
							<i class="fa-lg fa-solid fa-plus"></i>  新增菜單
						</div>
					</th>
				</tr>
			</thead>
			<tbody>
			@foreach($menus as $nKey => $menu)
				<tr>
					<td width="20%">
						<img class="menu-table-image" name="ImagePreview" src="{{ $menu->ImageSrc }}">
					</td>
					<td>{{ $menu->Name }}</td>
					<td>{{ $menu->Price }}</td>
					<td>{{ $menu->OnSale == 0 ? '無特價' : $menu->OnSale }}</td>
					<td>{{ $menu->StatusStr }}</td>
					<td>
						<a href="/font/menu_manger_edit?token={{ $menu->Token }}">
							<i class="fa-lg fa-solid fa-pen-to-square"></i>
						</a>
						<a class="ml-2" href="javascript:delete_menu('{{ $menu->Token }}');">
							<i class="fa-lg fa-solid fa-trash-can"></i>
						</a>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>

	@include('/common/modal')

@endsection
