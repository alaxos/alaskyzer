<?php
use Cake\Routing\Router;
?>

<ul class="list-group" id="applications_list">

</ul>


<script type="text/javascript">
var applications_by_id = {};

function get_applications(async)
{
	var applications = null;
	
	if(typeof(async) == "undefined")
    {
    	async = false;
    }
	
	$.ajax({
		url      : "<?php echo Router::url(['controller' => 'Applications', 'action' => 'getList']);?>.json",
		dataType : "json",
		async : async
	})
	.done(function(data, textStatus, jqXHR){
		applications = data;
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		
	});

	return applications;
}

function fill_applications(async)
{
	var applications = get_applications();
	fill_applications_list(applications);
}

function fill_applications_list(applications)
{
	applications_by_id = {}
	$("#applications_list").html("");
	
	$.each(applications, function(index, application){

		applications_by_id[application.id] = application;

	    var application_item = "<a class=\"list-group-item application_line\" id=\"application_item_" + application.id + "\" data-application-id=\"" + application.id + "\">" + application.name;
        application_item += "<span class=\"badge\">";
        application_item += "</span>";
	    application_item += "</a>";
	    
		$("#applications_list").append(application_item);
	});

	$.each(applications, function(index, application){

		var active_tasks_total = 0;
		$.each(application.tasks, function(i, task){
			if(task.closed == null && task.abandoned == null){
				active_tasks_total++;
			}
		});
		if(active_tasks_total > 0)
		{
		    $("#application_item_" + application.id + " span.badge").html(active_tasks_total);
		}
		else
		{
			$("#application_item_" + application.id + " span.badge").hide();
		}
	});

	register_applications_select();
}

function register_applications_select()
{
	$("#applications_list a").click(function(e){

		e.preventDefault();
	    
		select_application($(this).attr("data-application-id"));
	});
}

function select_application(application_id)
{
	clear_tasks();
	
	$("#applications_list a").removeClass("active");
	$("#application_item_" + application_id).addClass("active");
    
	var application = applications_by_id[application_id];

	fill_tasks(application.tasks);

	$(location).attr("hash", application_id);
}
</script>