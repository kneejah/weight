function getWeightData(currentTs, daysBack, customValue)
{
	$.ajax({
		dataType: "json",
		url:      "/api/weights",
		type:     "GET",
		data:     { current_ts: currentTs, days_back: daysBack, custom_value: customValue },
		success:  function(json) {
			drawChart(json.result);
		}
	});
}

function getStatsData(currentTs, daysBack, customValue)
{
	$.ajax({
		dataType: "json",
		url:      "/api/userdata",
		type:     "GET",
		data:     { current_ts: currentTs, days_back: daysBack, custom_value: customValue },
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

		if (target_weight != "N/A" && weight_to_target != 0)
		{
			var target_weight_val = $('#target_weight').html() + " (" + weight_to_target + " " + result.units + " to go)";
			$('#target_weight').html(target_weight_val);

			$('#time_to_target').html(time_to_target);
			$('#time_to_target_desc').show();
		}
		else
		{
			$('#time_to_target').hide();
			$('#time_to_target_desc').hide();
		}

	}
}

function startGraph()
{
        var currentTs = Math.round((new Date()).getTime() / 1000);
	var daysBackVal = $('#filter_picker').val();

	if (daysBackVal != "custom")
	{
		$('#custom_range_picker').hide();

		getWeightData(null, daysBackVal, false);
		getStatsData(null, daysBackVal, false);

		resizeGraph();
	}
	else
	{
		$('#custom_range_picker').show();

        	var customStartDateField = $('#custom_start_date').data('date');
		var customEndDateField = $('#custom_end_date').data('date');

		if (customStartDateField != '' && customEndDateField != '')
		{
			var customStartDate = Date.parse(customStartDateField) / 1000;
			var customEndDate = Date.parse(customEndDateField) / 1000;

			var customDateRange = (customEndDate - customStartDate) / 86400;

			if (customDateRange > 0)
			{
                		getWeightData(customEndDate, customDateRange, true);
                		getStatsData(customEndDate, customDateRange, true);

                		resizeGraph();
			}
		}
	}
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
		$('#comment').val('');

		var picker = $('#datetimepicker').data('datetimepicker');
		picker.setLocalDate(null);

		startGraph();
	}
}

function resizeGraph() {
	var min = 350;
	var max = 1200;
	var width = $(window).width();

	var newWidth = 0;

	if (width < min)
	{
		newWidth = min;
	}
	else if (width > max) {
		newWidth = max;
	}
	else {
		newWidth = width;
	}

	if (newWidth < 1200)
	{
		newWidth = newWidth - 75;
	}

	$("#chart_div").css("width", newWidth + "px");
	drawChart();
}

function addWeight(el)
{
	var picker = $('#datetimepicker').data('datetimepicker');
	var dateVal = picker.getLocalDate();
        var dateTs = new Date(dateVal).getTime() / 1000;
        dateTs = Math.round(dateTs);

	var weightVal  = $('#weight').val();
	var commentVal = $('#comment').val();

	$.ajax({
		dataType: "json",
		url:      "/api/weight",
		type:     "POST",
		data:     { weight: weightVal, date: dateTs, comment: commentVal },
		success:  function(json) {
			processResult(json);
		}
	});
}

function drawChart(jsonData) {

	if (jsonData)
	{
		window.jsonData = jsonData;
	}
	else if (window.jsonData)
	{
		jsonData = window.jsonData;
	}
	else {
		return;
	}

	var data = new google.visualization.DataTable();
	data.addColumn('datetime', 'Year');
	data.addColumn('number',   'Trend line');
	data.addColumn('number',   'Target weight');
	data.addColumn('number',   'My weight');
	data.addColumn( { type: 'string', role: 'tooltip', 'p': { 'html': true } } );

	var newData  = jsonData.data;

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

		var commentVal = generateTooltip(dateNum, weightNum, comment, jsonData.units);
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
		tooltip:          { isHtml: true },
		legend:           { position: showLegend, alignment: 'end' },
		interpolateNulls: true,
		curveType:        graph_smoothing,
		colors:           [ '#A83232', '#00563F', '#3266CC' ],
		series:           [ { visibleInLegend: showTrendLegend }, { visibleInLegend: showTargetLegend }, { visibleInLegend: true } ]
	};

	var chart_div = document.getElementById('chart_div');
	var chart = new google.visualization.LineChart(chart_div);
	chart.draw(data, options);
}

function generateTooltip(dateNum, weightNum, comment, units)
{
	var s = "<div style='font-weight: bold; padding: 5px;'>" + formatTimestampToDate(dateNum)
		+ "<br />Weight: " + weightNum + " " + units;

	if (comment != "")
	{
		s = s + "<br />Comment: " + '"' + comment + '"';
	}

	s = s + "</div>";

	return s;
}
