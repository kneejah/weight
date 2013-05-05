var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August",
				  "September", "October", "November", "December"];

var dayNames = ["Sun", "Mon", "Tues", "Wed", "Thurs", "Fri", "Sat"];

function showErrorMessage(message)
{
	$('#success_div').hide();
	$('#error_div').hide();
	$('#error_div').html('Error: ' + message);
	$('#error_div').fadeIn();
}

function showSuccessMessage(message)
{
	$('#error_div').hide();
	$('#success_div').hide();
	$('#success_div').html(message);
	$('#success_div').fadeIn();
}