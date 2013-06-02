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

function getStatsData(daysBack)
{
	$.ajax({
		dataType: "json",
		url:      "/api/userdata",
		type:     "GET",
		data:     {days_back: daysBack},
		success:  function(json) {
			drawStatsData(json);
		}
	});
}

function drawStatsData(data)
{
	if (data.success)
	{
		result = data.result;

		var bmi              = result.data.bmi;
		var min              = result.data.stats.min;
		var max              = result.data.stats.max;
		var avg              = result.data.stats.avg;
		var change_weight    = result.data.stats.change_weight;
		var change_per_day   = result.data.stats.change_per_day;
		var change_per_week  = result.data.stats.change_per_week;
		var change_per_month = result.data.stats.change_per_month;

		var target_weight    = result.data.target.target_weight;
		var weight_to_target = result.data.target.weight_to_target;
		var time_to_target   = result.data.target.time_to_target;

		var bmi_val = bmi;
		if (bmi == "no_weights")
		{
			bmi_val = "N/A";
		}
		else if (bmi == "no_height")
		{
			bmi_val = '(<a href="/settings">add height</a>)';
		}
		else
		{
			var bmi_descrip = "";

			if (bmi_val >= 30.0)
			{
				bmi_descrip = "Obese";
			}
			else if (bmi_val >= 25.0)
			{
				bmi_descrip = "Overweight";
			}
			else if (bmi_val >= 18.5)
			{
				bmi_descrip = "Normal weight";
			}
			else
			{
				bmi_descrip = "Underweight";
			}

			$('#bmi').attr('title', bmi_descrip);
		}

		$('#bmi').html(bmi_val);
		$('#min_weight').html(min);
		$('#max_weight').html(max + (max != "N/A" ? " " + result.units : ""));
		$('#avg_weight').html(avg + (avg != "N/A" ? " " + result.units : ""));
		$('#change_weight').html(change_weight + (change_weight != "N/A" ? " " + result.units : ""));
		$('#change_per_day').html(change_per_day);
		$('#change_per_week').html(change_per_week);
		$('#change_per_month').html(change_per_month + (change_per_month != "N/A" ? " " + result.units : ""));

		$('#target_weight').html(target_weight + (target_weight != "N/A" ? " " + result.units : ""));

		if (target_weight != "N/A")
		{
			$('#weight_to_target').html(weight_to_target + (weight_to_target != "N/A" ? " " + result.units : ""));
			$('#time_to_target').html(time_to_target);
			$('#weight_to_target_desc').show();
			$('#time_to_target_desc').show();
		}
		else
		{
			$('#weight_to_target').hide();
			$('#time_to_target').hide();
		}

	}
}

function startGraph()
{
	var val = $('#filter_picker').val();
	getWeightData(val);
	getStatsData(val);
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
	data.addColumn('datetime', 'Year');
	data.addColumn('number',   'Trend line');
	data.addColumn('number',   'Target weight');
	data.addColumn('number',   'My weight');
	data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});

	var newData  = jsonData.data;
	var tzOffset = jsonData.tz_offset * (60 * 60 * 1000);

	var showTrendLine = window.user_options.trend_line;
	var targetWeight  = window.user_options.target_weight;
	var showTargetWeight = window.user_options.show_target_weight;
	var showLegend    = 'none';

	for (var i in newData)
	{
		var tmp = newData[i];

		var dateNum    = parseInt(tmp['date']) * 1000;
		var parsedDate = new Date(dateNum);
		var weightNum  = parseFloat(tmp['weight']);
		var comment    = tmp['comment'];

		var commentVal = generateTooltip(parsedDate, weightNum, comment, jsonData.units);
		var trendVal   = null;
		var targetVal  = null;

		var showTrendLegend = false;
		var showTargetLegend = false;

		if ( (i == 0 || i == (newData.length - 1)) && newData.length > 2 && showTrendLine == true)
		{
			trendVal   = weightNum;
			showLegend = 'in';
			showTrendLegend = true;
		}

		if ( (i == 0 || i == (newData.length - 1)) && newData.length > 1 && targetWeight > 0 && showTargetWeight)
		{
			targetVal  = targetWeight;
			showLegend = 'in';
			showTargetLegend = true;
		}

		data.addRow([
			parsedDate,
			trendVal,
			targetVal,
			weightNum,
			commentVal
		]);
	}

	var graph_smoothing = (window.user_options.graph_smoothing) ? "function" : "none";

	var options = {
		pointSize:        4,
		tooltip:          {isHtml: true},
		legend:           {position: showLegend, alignment: 'end'},
		interpolateNulls: true,
		curveType:        graph_smoothing,
		colors:           ['#A83232', '#00563F', '#3266CC'],
		series:           [{visibleInLegend: showTrendLegend}, {visibleInLegend: showTargetLegend}, {visibleInLegend: true}]
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

	var hrSuffix  = (hrs >= 12) ? "pm" : "am"; 
	var convHours = (hrs >= 12) ? hrs - 12 : hrs;
	if (convHours == 0) convHours = 12;

	var s = "<div style='font-weight: bold; padding: 5px;'>" + dayNames[parsedDate.getDay()]
		+ " " + monthNames[parsedDate.getMonth()] + " " + parsedDate.getDate()
		+ ", " + parsedDate.getFullYear() + ", " + convHours + ":"
		+ mins + " " + hrSuffix + "<br />Weight: " + weightNum + " " + units;

	if (comment != "")
	{
		s = s + "<br />Comment: " + '"' + comment + '"';
	}

	s = s + "</div>";

	return s;
}
