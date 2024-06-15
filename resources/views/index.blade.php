<?php use App\Constant\Constant; ?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="/images/common/logo.png" type="image/icon type">
	<title>Order System</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<script src="https://kit.fontawesome.com/bd5e738029.js" crossorigin="anonymous"></script>
	<script src="/js/common/common.js"></script>
	<script src="/js/index.js"></script>
	@yield('js')

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="/css/common/common.css">
	<link rel="stylesheet" href="/css/index.css">
	@yield('css')
</head>
<body>
	<input type="hidden" name="page" value="{{ $page }}">

	<div class="col-12 row justify-content-center p-0 m-0">
		<div class="col-12 d-flex justify-content-center mt-2">
			<div class="ml-auto"></div>
			<label class="m-0 tilte">Order System</label>
			<div class="ml-auto btn" onclick="javascript:switch_mode();"><i class="px-1 fa-solid fa-repeat"></i></div>
		</div>
		<div class="col-10 d-flex justify-content-center mt-3">
			<div class="col-12 row btn-row p-0">
				@if($mode == 'font')
					<div class="py-1 func-btn" id="order_manger" onclick="javascript:goto('/font/order_manger');">訂單管理</div>
					<div class="py-1 func-btn" id="menu_manger" onclick="javascript:goto('/font/menu_manger');">菜單管理</div>
					<div class="py-1 func-btn" id="table_manger" onclick="javascript:goto('/font/table_manger');">桌次管理</div>
					<div class="py-1 func-btn last-btn" id="statistic" onclick="javascript:goto('/font/statistic');">統計管理</div>
				@elseif($mode == 'back')
					<div class="py-1 func-btn-2" id="internal" onclick="javascript:goto('/back/order?mode=back&type=<?php echo constant::ORDER_TYPE_INTERNAL?>');">內用訂單</div>
					<div class="py-1 func-btn-2 last-btn" id="takeaway" onclick="javascript:goto('/back/order?mode=back&type=<?php echo constant::ORDER_TYPE_TAKEAWAY?>');">外帶訂單</div>
				@endif
			</div>
		</div>
		<div class="col-11 mt-4 p-0 content-wrap">
			@yield('content')
		</div>
	</div>
</body>
</html>
