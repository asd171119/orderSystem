var tables;

$(document).ready(function ()
{
	tables = new DataTable($('#tables'),
	{
		searching: false,
		info:false,
		paging: false,
		ordering: false,
	});
});

function goto_tableMangerEdit()
{
	window.location = '/font/table_manger_edit';
}

function delete_table(token)
{
	show_modal('normal', 'warning', '確定要刪除嗎?', '刪除後無法復原。', 'sm', '確認', '取消', function ()
	{
		window.location = '/font/table_manger_delete?token=' + token;
	}, null);
}
