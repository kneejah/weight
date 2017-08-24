var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August",
				  "September", "October", "November", "December"];

var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

var fadeDelay = 10000; // 10 seconds

function formatTimestampToDate(tsInMillis)
{
	var parsedDate = new Date(tsInMillis);

	var hrs = parsedDate.getHours();
	var mins = parsedDate.getMinutes();
	if (mins < 10) mins = "0" + mins;

	var hrSuffix  = (hrs >= 12) ? "PM" : "AM";
	var convHours = (hrs >= 12) ? hrs - 12 : hrs;
	if (convHours == 0) convHours = 12;

	var s = dayNames[parsedDate.getDay()] + " " + monthNames[parsedDate.getMonth()]
		+ " " + parsedDate.getDate() + ", " + parsedDate.getFullYear() + ", "
		+ convHours + ":" + mins + " " + hrSuffix;

	return s;
}

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
