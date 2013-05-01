var monthNames = ["January", "February", "March", "April", "May", "June",
	"July", "August", "September", "October", "November", "December"];

function showErrorMessage(message)
{
	$('#success_div').hide();
	$('#error_div').html('Error: ' + message);
	$('#error_div').show();
}

function showSuccessMessage(message)
{
	$('#error_div').hide();
	$('#success_div').html(message);
	$('#success_div').show();
}