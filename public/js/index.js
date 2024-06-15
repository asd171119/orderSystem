
$(document).ready(function ()
{
	btn_select($('input[type=hidden][name=page]').val())
});

function btn_select(page)
{
	$('.btn-func').removeClass('btn-selected');
	$('#' + page).addClass('btn-selected');
}

function goto(dir)
{
	window.location = dir;
}

function switch_mode()
{
	var mode = get_URLParams('mode');

	switch (mode)
	{
		case 'font':
			mode = 'back';
			break;

		default:
			mode = 'font';
			break;
	}

	window.location = '/switch?mode=' + mode + '&type=1';
}
