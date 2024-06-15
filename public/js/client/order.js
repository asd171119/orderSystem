$(document).ready(function()
{

});

function order_btn(MenuID, Price, mode)
{
	if(mode == 'add')
	{
		var oriAmount = parseInt($('#menu-' + MenuID).val());
		var amount = oriAmount + 1;

		$('#menu-' + MenuID).val(amount);
		$('#amount-' + MenuID).text(amount);

		var oriTotal = parseInt($('#total-price').data('total'));
		var total = (oriTotal + parseInt(Price));

		$('#total-price').data('total', total);
		$('#total-price').text('$' + total);
	}
	else
	{
		var oriAmount = parseInt($('#menu-' + MenuID).val());
		var amount = (oriAmount - 1) < 0 ? 0 : (oriAmount - 1);

		if(oriAmount != 0)
		{
			$('#menu-' + MenuID).val(amount)
			$('#amount-' + MenuID).text((amount))
		
			var oriTotal = parseInt($('#total-price').data('total'));
			var total = (oriTotal - parseInt(Price)) < 0 ? 0 : (oriTotal - parseInt(Price));
			$('#total-price').data('total', total);
			$('#total-price').text('$' + total);
		}

	}
}