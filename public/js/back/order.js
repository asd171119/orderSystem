$(document).ready(function ()
{
	get_params();

	refresh();
});

function finish_orderDetail(OrderDetailID)
{
	window.location = '/back/orderDetail_finish?id=' + OrderDetailID + '&type=' + get_URLParams('type');
}

function refresh()
{
	if(typeof timer != 'undefined') clearInterval(timer);

	timer = setInterval(function ()
	{
		window.location.reload();
	}, REFRESH_DURATION * 1000);
}

