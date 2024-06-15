
$(document).ready(function ()
{
	$('input[name=range]').daterangepicker(
		{
			timePicker			: false,
			// minDate				: moment(),
			timePicker24Hour	: false,
			// timePickerIncrement	: 5,
			singleDatePicker	: false,
			locale				:
			{
				format			: 'YYYY-MM-DD'
			}
		});
});

function statistic()
{
	var from = $('input[name=range]').data('daterangepicker').startDate.format('YYYY-MM-DD');
	var till = $('input[name=range]').data('daterangepicker').endDate.format('YYYY-MM-DD');

	window.location = '/font/statistic?from=' + from + '&till=' + till;
}

