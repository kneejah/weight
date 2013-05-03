function getWeightData(daysBack)
{
	$.ajax({
		dataType: "json",
		url:      "/api/weights",
		type:     "GET",
		data:     {days_back: daysBack},
		success:  function(json) {
			drawChart(json.result);
		}
	});
}

function startGraph()
{
	var val = $('#filter_picker').val();
	getWeightData(val);
}

function processResult(data)
{
	if (data.success == false)
	{
		showErrorMessage(data.error);
	}
	else
	{
		showSuccessMessage("Weight successfully updated.");

		$('#weight').val('');
		$('#date').val('');
		$('#comment').val('');

		startGraph();
	}
}

function addWeight(el)
{
	var weightVal  = $('#weight').val();
	var dateVal    = $('#date').val();
	var commentVal = $('#comment').val();

	$.ajax({
		dataType: "json",
		url:      "/api/weight",
		type:     "POST",
		data:     {weight: weightVal, date: dateVal, comment: commentVal},
		success:  function(json) {
			processResult(json);
		}
	});
}

function drawChart(jsonData) {

	var data = new google.visualization.DataTable();
	data.addColumn('datetime',   'Year');
	data.addColumn('number',     'Weight');
	data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});

	var newData = jsonData.data;
	for (var i in newData)
	{
		var tmp = newData[i];

		var dateNum    = parseInt(tmp['date']) * 1000;
		var parsedDate = new Date(dateNum);
		var weightNum  = parseFloat(tmp['weight']);
		var comment    = tmp['comment'];

		var commentVal = generateTooltip(parsedDate, weightNum, comment, jsonData.units);

		data.addRow([
			parsedDate,
			weightNum,
			commentVal
		]);
	}

	var graph_smoothing = (window.user_options.graph_smoothing) ? "function" : "none";

	var options = {
		title:     "My Weight",
		pointSize: 4,
		tooltip:   {isHtml: true},
		legend:    {position: 'none'},
		curveType: graph_smoothing
	};

	var chart_div = document.getElementById('chart_div');
	var chart = new google.visualization.LineChart(chart_div);
	chart.draw(data, options);
}

function generateTooltip(parsedDate, weightNum, comment, units)
{
	var hrs = parsedDate.getHours();
	var mins = parsedDate.getMinutes();
	if (mins < 10) mins = "0" + mins;

	var hrSuffix  = (hrs > 12) ? "pm" : "am"; 
	var convHours = (hrs > 12) ? hrs - 12 : hrs;
	if (convHours == 0) convHours = 12;

	var s = "<div style='font-weight: bold; padding: 5px;'>"
		+ monthNames[parsedDate.getMonth()] + " " + parsedDate.getDate()
		+ ", " + parsedDate.getFullYear() + ", " + convHours + ":"
		+ mins + " " + hrSuffix + "<br />Weight: " + weightNum + " " + units;

	if (comment != "")
	{
		s = s + "<br />Comment: " + '"' + comment + '"';
	}

	s = s + "</div>";

	return s;
}