var timer;

$(document).ready(function ()
{
	tables = new DataTable($('#tables'),
	{
		searching: false,
		info:false,
		paging: false,
		ordering: false,
	});

	get_params();

	refresh();
});

function reserve(TableStatusID)
{
	clearInterval(timer);

	var html = 	'<div class="col-12 mt-2">' +
					'<label>電話：</label>' +
					'<input name="Phone" autocomplete="off"></input>' +
				'</div>' +
				'<div class="col-12 mt-2">' +
					'<label>時間：</label>' +
					'<input name="Reserve" autocomplete="off"></input>' +
				'</div>' +
				'<input type="hidden" name="TableStatusID" value="' + TableStatusID + '">';

	show_modal('form', 'normal','訂位服務', html, 'md', '確認', '取消', function ()
	{
		$('form').attr('action', '/font/table_reserve');
	}, null);

	$('#modal').on('shown.bs.modal', function ()
	{
		$('input[name=Reserve]').daterangepicker(
		{
			timePicker			: true,
			minDate				: moment(),
			timePicker24Hour	: true,
			timePickerIncrement	: 5,
			singleDatePicker	: true,
			locale				:
			{
				format			: 'YYYY-MM-DD HH:mm'
			}
		});
	})

	$('#modal').on('hidden.bs.modal', function ()
	{
		refresh();
	})
}

function cancel_reserve(TableStatusID)
{
	clearInterval(timer);

	var html = '<input type="hidden" name="TableStatusID" value="' + TableStatusID + '">';

	show_modal('form', 'normal','確定要取消訂位嗎?', html, 'sm', '確認', '取消', function ()
	{
		$('form').attr('action', '/font/table_reserve_cancel');
	}, null);

	$('#modal').on('hidden.bs.modal', function ()
	{
		refresh();
	})
}

function finish(TableStatusID)
{
	clearInterval(timer);

	var html = '<input type="hidden" name="TableStatusID" value="' + TableStatusID + '">';

	show_modal('form', 'normal','確定將桌次狀態恢復為空桌嗎?', html, 'sm', '確認', '取消', function ()
	{
		$('form').attr('action', '/font/table_finish');
	}, null);

	$('#modal').on('hidden.bs.modal', function ()
	{
		refresh();
	})
}

function refresh()
{
	if(typeof timer != 'undefined') clearInterval(timer);

	timer = setInterval(function ()
	{
		window.location = '/font/order_manger?mode=' + get_URLParams('mode');
	}, REFRESH_DURATION * 1000);
}

function show_orderDetail(OrderID)
{
	clearInterval(timer);

	var detailHtml = '';
	for(var OrderDetailID in orderDatas[OrderID]['Details'])
	{
		var MenuName	= orderDatas[OrderID]['Details'][OrderDetailID]['MenuName'];
		var StatusStr	= orderDatas[OrderID]['Details'][OrderDetailID]['StatusStr'];
		var Status		= orderDatas[OrderID]['Details'][OrderDetailID]['Status'];
		var Price		= orderDatas[OrderID]['Details'][OrderDetailID]['Price'];
		var Amount		= orderDatas[OrderID]['Details'][OrderDetailID]['Amount'];

		var editBtn = '<div class="col-2"></div>';
		if(Status != ORDER_DETAIL_STATUS_CANCEL)
		{
			editBtn = '<div class="col-2 btn" onclick="javascript:edit_orderDetail(\'' + OrderID + '\', ' + OrderDetailID + ');">編輯</div>';
		}

		detailHtml += '<div class="col-12 row m-0 mt-1 p-0 align-items-center" id="OrderDetail-' + OrderDetailID + '" data-orderdetailid=' + OrderDetailID + '>' +
							'<label class="col-5 mb-0 p-0">' + MenuName + ' (' + StatusStr + ')</label>' +
							'<label class="col-1 mb-0 p-0 ml-auto">' + Price + '</label>' +
							'* ' +
							'<label class="col-1 mr-2 mb-0 ">' + Amount + '</label>' +
							editBtn +
						'</div>';
	}

	var html = '<div class="col-12 m-0 p-0 d-flex row">' +
					'<div class="col-12 mt-2 p-0 d-flex">' +
						'<label class="mb-0">訂單編號： ' + OrderID + '</label>' +
						'<label class="ml-auto mr-5 mb-0">' + orderDatas[OrderID].StatusStr + '</label>' +
					'</div>' +
					'<hr/>' +
					'<div class="col-12 mt-2">' +
						detailHtml +
					'</div>' +
					'<hr/>' +
					'<label class="ml-auto m-0 mb-0 p-0">總金額：</label>' +
					'<label class="mr-3 mb-0" name="Total">' + orderDatas[OrderID].Total + '</label>' +
				'</div>';

	show_modal('form', 'normal','訂單資訊', html, 'md', '確認', '取消', function ()
	{
		var editData = [];
		$('form').find('.edit').each(function ()
		{
			editData.push({ID : $(this).data('orderdetailid'), Amount : $(this).find('label[name=Amount]').text()})
		});

		editData = (JSON.stringify(editData)).replace(/"/g, "'");

		$('form').append('<input type="hidden" name="editData" value="' + editData + '">')


		$('form').attr('action', '/font/order_detail_edit');
	}, null);

	$('#modal').on('hidden.bs.modal', function ()
	{
		refresh();
	})
}

function edit_orderDetail(OrderID, OrderDetailID)
{
	clearInterval(timer);

	var MenuName	= orderDatas[OrderID]['Details'][OrderDetailID]['MenuName'];
	var StatusStr	= orderDatas[OrderID]['Details'][OrderDetailID]['StatusStr'];
	var Price		= orderDatas[OrderID]['Details'][OrderDetailID]['Price'];
	var Amount		= orderDatas[OrderID]['Details'][OrderDetailID]['Amount'];

	var detailHtml = '<div class="col-12 row m-0 mt-1 p-0 align-items-center" id="OrderDetail-"' + OrderDetailID + '>' +
						'<label class="col-4 mb-0 p-0">' + MenuName + ' (' + StatusStr + ')</label>' +
						'<label class="col-1 p-0 mb-0 ml-auto" name="Price">' + Price + '</label>' +
						'* ' +
						'<label class="col-1 mr-2 mb-0" name="Amount">' + Amount + '</label>' +
						'<div class="col-1 mr-2 btn" name="add">+</div>' +
						'<div class="col-1 mr-2 btn" name="sub">-</div>' +
						'<div class="col-2 btn" onclick="javascript:edit_orderDetail(' + OrderDetailID + ');">編輯</div>' +
					'</div>';

	$('#OrderDetail-' + OrderDetailID).html(detailHtml);

	$('#OrderDetail-' + OrderDetailID).find('div[name=add]').unbind('click');
	$('#OrderDetail-' + OrderDetailID).find('div[name=add]').on('click', function ()
	{
		var Amount = parseInt($('#OrderDetail-' + OrderDetailID).find('label[name=Amount]').text());

		$('#OrderDetail-' + OrderDetailID).find('label[name=Amount]').text(Amount + 1);

		var Price = parseInt($('#OrderDetail-' + OrderDetailID).find('label[name=Price]').text());
		var Total = parseInt($('#modal').find('label[name=Total]').text());
		$('#modal').find('label[name=Total]').text(Total + Price);

		// 標記修改
		$('#OrderDetail-' + OrderDetailID).addClass('edit');
	});

	$('#OrderDetail-' + OrderDetailID).find('div[name=sub]').unbind('click');
	$('#OrderDetail-' + OrderDetailID).find('div[name=sub]').on('click', function ()
	{
		var Amount = parseInt($('#OrderDetail-' + OrderDetailID).find('label[name=Amount]').text());
		if(Amount - 1 >= 0)
		{
			$('#OrderDetail-' + OrderDetailID).find('label[name=Amount]').text(Amount - 1);

			var Price = parseInt($('#OrderDetail-' + OrderDetailID).find('label[name=Price]').text());
			var Total = parseInt($('#modal').find('label[name=Total]').text());
			$('#modal').find('label[name=Total]').text(Total - Price);

			// 標記修改
			$('#OrderDetail-' + OrderDetailID).addClass('edit');
		}
	});

	$('#modal').on('hidden.bs.modal', function ()
	{
		refresh();
	})

}

function paid_order(OrderID)
{
	clearInterval(timer);

	$.ajax
	({
		type: 'Post',
		url: '/apis/order_detail_check',
		data: {OrderID : orderDatas[OrderID]['ID'], _token : $('#token').val()},
		success: function (response)
		{
			var Total = orderDatas[OrderID]['Total']
			var Status = response ? '' : '<label class="text-danger">請注意，此訂單尚有未完成餐點。</label>';

			var html = '<h4>總金額：' + Total + '</h4>' + Status;

			show_modal('normal', 'warning', '確認訂單結賬', html, 'sm', '確認', '取消', function name(params)
			{
				window.location = '/font/order_manger_pay?oid=' + orderDatas[OrderID]['ID'];
			}, null);
		},
		error: function (xhr, exception)
		{
			var error = 'error code : ' + xhr.status + ' ( ' + xhr.statusText + ' )';
			if(xhr.status === 0 || xhr.status === 404 || xhr.status === 403 || xhr.status === 500)
			{
				error = 'error code : ' + xhr.status + ' ( ' + xhr.statusText + ' )';
			}
			else if(exception === 'parsererror' || exception === 'timeout' || exception === 'abort')
			{
				error = exception;
			}

			show_modal('normal', 'warning', '發生錯誤', error, 'sm', '', '', null, null);
		},
	});

	$('#modal').on('hidden.bs.modal', function ()
	{
		refresh();
	})
}
