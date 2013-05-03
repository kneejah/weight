function updateRecord(id)
{
	var weight  = $('#weight_' + id).val();
	var comment = $('#comment_' + id).val();

	$.ajax({
		dataType: "json",
		url:      "/api/weight",
		type:     "PUT",
		data:     {id: id, weight: weight, comment: comment},
		success:  function(json) {
			updateProcess(json);
		}
	});
}

function updateProcess(data)
{
	if (data.success == false)
	{
		showErrorMessage(data.error);
	}
	else
	{
		showSuccessMessage("Record successfully updated.");
	}
}

function deleteRecord(id)
{
	var sure = confirm("Are you sure you want to delete this record?");
	if (!sure)
	{
		return;
	}
	else
	{
		$.ajax({
			dataType: "json",
			url:      "/api/weight",
			type:     "DELETE",
			data:     {id: id},
			success:  function(json) {
				deleteProcess(json);
			}
		});
	}
}

function deleteProcess(data)
{
	if (data.success == false)
	{
		showErrorMessage(data.error);
	}
	else
	{
		var id = data.result.id;
		showSuccessMessage("Record successfully deleted.");
		$('#row_' + id).fadeOut('slow');
	}
}