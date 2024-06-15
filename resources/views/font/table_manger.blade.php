
@extends('index')

@section('js')
	<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="/js/font/table_manger.js"></script>
@endsection

@section('css')
	<link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="/css/font/table.css">
@endsection

@section('content')
	<div class="col-12">
		<table class="my-3" id="tables" width="50%">
			<thead>
				<tr>
					<th><label class="px-3 th-label">名稱</label></th>
					<th><label class="px-3 th-label">人數</label></th>
					<th><label class="px-3 th-label">狀態</label></th>
					<th>
						<div class="btn insert-btn" onclick="javascript:goto_tableMangerEdit();">
							<i class="fa-lg fa-solid fa-plus"></i>  新增桌次
						</div>
					</th>
				</tr>
			</thead>
			<tbody>
			@foreach($tables as $nKey => $table)
				<tr>
					<td>{{ $table->Name }}</td>
					<td>{{ $table->People }}</td>
					<td>{{ $table->StatusStr }}</td>
					<td>
						<a href="/font/table_manger_edit?token={{ $table->Token }}">
							<i class="fa-lg fa-solid fa-pen-to-square"></i>
						</a>
						<a class="ml-2" href="javascript:delete_table('{{ $table->Token }}');">
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
