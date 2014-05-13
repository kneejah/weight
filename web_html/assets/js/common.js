var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August",
				  "September", "October", "November", "December"];

var dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

var fadeDelay = 10000; // 10 seconds

function showErrorMessage(message)
{
	$('#success_div').hide();
	$('#error_div').hide();
	$('#error_div').html('Error: ' + message);
	$('#error_div').fadeIn();

        setTimeout(function()
        {
                $('#error_div').fadeOut("slow", function()
                {
                        $('#error_div').html("&nbsp;");
                        $('#error_div').show();
                });
        }, fadeDelay);

}

function showSuccessMessage(message)
{
	$('#error_div').hide();
	$('#success_div').hide();
	$('#success_div').html(message);
	$('#success_div').fadeIn();

	setTimeout(function()
        {
                $('#success_div').fadeOut("slow", function()
		{
			$('#success_div').html("&nbsp;");
			$('#success_div').show();
		});
        }, fadeDelay);
}
