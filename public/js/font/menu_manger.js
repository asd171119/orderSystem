var menus;

$(document).ready(function ()
{
	menus = new DataTable($('#menus'),
	{
		searching: false,
		info:false,
		paging: false,
		ordering: false,
	});
});

function goto_menuMangerEdit()
{
	window.location = '/font/menu_manger_edit';
}

function delete_menu(token)
{
	show_modal('normal', 'warning', '確定要刪除嗎?', '刪除後無法復原。', 'sm', '確認', '取消', function ()
	{
		window.location = '/font/menu_manger_delete?token=' + token;
	}, null);
}


