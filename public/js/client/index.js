var index_img = 0;

$(document).ready(function()
{
	init_slider();
});

function init_slider()
{
	var PreBtn = $('#pre-btn');
	var NextBtn = $('#next-btn');

	for (var i = 0; i < $('.slides').length; i++) 
	{
		var now_dot = i == 0 ? 'now-dot' : '';

		$('.dots').append('<div class="dot ' + now_dot + '" data-index="' + i + '"></div>');
	}

	$('.slides').each(function (index)
	{
		$(this).css('left', index * 100 + '%');
	});

	setInterval(function ()
	{
		if(index_img == ($('.slides').length - 1))
		{
			index_img = 0;
		}
		else
		{
			index_img += 1;
		}

		slide_img();
	}, 5000);

	PreBtn.unbind('click');
	PreBtn.on('click', function ()
	{
		index_img = (index_img - 1) < 0 ? ($('.slides').length - 1) : index_img - 1;

		slide_img();
	});

	NextBtn.unbind('click');
	NextBtn.on('click', function ()
	{
		index_img = (index_img + 1) > ($('.slides').length - 1) ? 0 : index_img + 1;

		slide_img();
	});

	$('.dot').unbind('click');
	$('.dot').on('click', function ()
	{
		index_img = $(this).data('index');

		slide_img();
	});
}

function slide_img()
{
	$('.dot').removeClass('now-dot');
	$($('.dot')[index_img]).addClass('now-dot');

	$('.slides').each(function (slide)
	{
		$(this).css('transform', 'translateX(-' + index_img * 100 + '%)');
	});
}

function goto_menu()
{
	window.location = '/client/menu';
}

function goto_verify(type)
{
	window.location = '/client/verify?md=' + type;
}