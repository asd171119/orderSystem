$(document).ready(function ()
{
	$('#Status').unbind('change');
	$('#Status').on('change', function ()
	{
		$('input[name=Status]').val($(this).prop('checked') ? 1 : 2)
	});
});

function upload_image()
{
	$('#Image').click();

	$("#Image").change(function()
	{
		readURL(this);
	});
}

function delete_iamge(token)
{
	show_modal('normal', 'warning', '確定要刪除嗎?', '刪除後無法復原。', 'sm', '確認', '取消', function ()
	{
		window.location = '/font/menu_manger_delete_image?token=' + token;
	}, null);
}

function readURL(file)
{
	if(file.files && file.files[0])
	{
		var reader = new FileReader();

		reader.onload = function (e)
		{
			$('img[name=ImagePreview]').attr('src', e.target.result);
		}

		reader.readAsDataURL(file.files[0]);
	}
  }

