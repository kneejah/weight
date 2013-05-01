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

function deleteProcess(json)
{
	if (json.success)
	{
		var id = json.result.id;
		showSuccessMessage("Record successfully deleted!");
		$('#row_' + id).fadeOut('slow');
	}
}