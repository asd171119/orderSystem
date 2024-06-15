
function show_modal(type, style, title, content, size, confirmBtn, cancelBtn, confirmFunc, cancelFunc)
{
	var Modal		= $('#modal');
	var Size		= Modal.find('.modal-dialog')
	var Header		= Modal.find('.modal-header')
	var Title		= Modal.find('.modal-title');
	var Content		= Modal.find('.modal-body');
	var ConfirmBtn	= Modal.find('button[name=confirm]');
	var CancelBtn	= Modal.find('button[name=cancel]');

	Size.removeAttr('class');
	Size.attr('class', 'modal-dialog modal-' + size);

	Header.removeAttr('class');
	Header.attr('class', 'modal-header modal-' + style);

	Title.text(title);

	Content.html(content)

	ConfirmBtn.removeAttr('class');
	ConfirmBtn.attr('class', style == 'warning' ? 'danger-btn' : 'btn');
	ConfirmBtn.attr('type', type == 'form' ? 'submit' : 'button')
	ConfirmBtn.text(confirmBtn)

	CancelBtn.text(cancelBtn)

	if(cancelBtn == '') CancelBtn.addClass('hidden');
	if(confirmBtn == '') ConfirmBtn.addClass('hidden');

	ConfirmBtn.unbind('click');
	ConfirmBtn.on('click', function ()
	{
		if(typeof confirmFunc == 'function') confirmFunc();
	});

	CancelBtn.unbind('click');
	CancelBtn.on('click', function ()
	{
		if(typeof cancelFunc == 'function') cancelFunc();
	});

	Modal.modal('show');
}
