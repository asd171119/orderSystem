<?php

	use Illuminate\Support\Facades\Route;
	use App\Http\Controllers\Controller;
	use App\Http\Controllers\MenuController;
	use App\Http\Controllers\OrderController;
	use App\Http\Controllers\TableController;
	use App\Http\Controllers\StatisticController;
	use App\Http\Controllers\client\CMenuController;
	use App\Http\Controllers\client\COrderController;

	Route::get('/switch', [Controller::class, 'switch']);

	Route::group(['prefix' => 'font'], function ()
	{
		Route::get('/order_manger', [OrderController::class, 'get_data']);
		Route::get('/order_manger_pay', [OrderController::class, 'order_manger_pay']);
		Route::post('/order_detail_edit', [OrderController::class, 'edit_order_detail']);
		Route::post('/table_reserve', [OrderController::class, 'reserve']);
		Route::post('/table_reserve_cancel', [OrderController::class, 'cancel_reserve']);
		Route::post('/table_finish', [OrderController::class, 'table_finish']);

		Route::get('/menu_manger', [MenuController::class, 'get_menus']);
		Route::get('/menu_manger_edit', [MenuController::class, 'get_menu']);
		Route::post('/menu_manger_edit', [MenuController::class, 'edit_menu']);
		Route::get('/menu_manger_delete', [MenuController::class, 'delete_menu']);
		Route::get('/menu_manger_delete_image', [MenuController::class, 'delete_menu_image']);

		Route::get('/table_manger', [TableController::class, 'get_tables']);
		Route::get('/table_manger_edit', [TableController::class, 'get_table']);
		Route::post('/table_manger_edit', [TableController::class, 'edit_table']);
		Route::get('/table_manger_delete', [TableController::class, 'delete_table']);

		Route::get('/statistic', [StatisticController::class, 'get_statistic']);
	});

	Route::group(['prefix' => 'back'], function ()
	{
		Route::get('/order', [OrderController::class, 'get_orders']);
		Route::get('/orderDetail_finish', [OrderController::class, 'finish_orderDetail']);
	});

	Route::group(['prefix' => 'apis'], function ()
	{
		Route::post('/order_detail_check', [OrderController::class, 'check_orderDetailStatus']);
	});

	Route::group(['prefix' => 'client'], function ()
	{
		Route::get('/', function ()
		{
			return view('/client/index', []);
		});

		Route::get('/verify', function ()
		{
			return view('/client/verify', []);
		});
		Route::post('/verify', [COrderController::class, 'verify_data']);

		Route::get('/complete', [COrderController::class, 'save_order']);
		Route::post('/check_order', [COrderController::class, 'check_order']);
		Route::get('/{order}', [CMenuController::class, 'get_menus']);
		Route::get('/{menu}', [CMenuController::class, 'get_menus']);
	});
