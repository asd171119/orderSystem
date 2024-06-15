function generateToken()
{
	var d = Date.now();
	return 'xxxxxxxx-xxxx-yxxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c)
	{
		var r = (d + Math.random() * 16) % 16 | 0;
		d = Math.floor(d / 16);
		return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
	});
}

function get_params()
{
	var g_this = this;
	$('input[type=hidden].params').each(function ()
	{
		var Name = $(this)[0].name;
		var value = $(this).val();

		g_this[Name] = value;
	});
}

function get_URLParams(key = '')
{
	var URLParams = new URLSearchParams(window.location.search);
	var Param = URLParams.get(key);

	if(key == '')
	{
		var Params = Object.fromEntries(URLParams.entries());

		return Params;
	}

	return Param;
}
