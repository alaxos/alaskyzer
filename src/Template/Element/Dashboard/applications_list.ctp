<?php
use Cake\Routing\Router;
?>

<ul class="list-group" id="applications_list">

</ul>


<script type="text/javascript">

var applications_by_id   = {};
var selected_application = null;

function load_applications_data(async)
{
	if(typeof(async) == "undefined"){
        async = false;
    }
    
    $.ajax({
        url      : "<?php echo Router::url(['controller' => 'Applications', 'action' => 'getList']);?>.json",
        dataType : "json",
        async    : async
    })
    .done(function(data, textStatus, jqXHR){
        applications = data;

        /*
         * Fill application cache
         */
        applications_by_id = {};
        $.each(applications, function(i, application){
        	applications_by_id[application.id] = application;
        });
        
        $("#applications_list").trigger("applications.loaded", [applications]);
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        Alaxos.manage_ajax_error(jqXHR);
    });
}

function fill_applications_ui_list()
{
	var sorted_applications = [];
	$.each(applications_by_id, function(application_id, application){
		sorted_applications.push(application);
	});
	
    sorted_applications.sort(function(a, b){

        if(a.name > b.name){
            return 1;
        }
        
        return -1;
    });

	$.each(sorted_applications, function(application_id, application){
	    
        var application_item = "<a class=\"list-group-item application_line\" id=\"application_item_" + application.id + "\" data-application-id=\"" + application.id + "\">" + application.name;
        application_item += "<span class=\"badge\">";
        application_item += "</span>";
        application_item += "</a>";
        
        $("#applications_list").append(application_item);
    });

    $.each(sorted_applications, function(application_id, application){

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

    $("#applications_list").trigger("applications.ui_list_populated", [applications_by_id]);
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
	if(selected_application == null || selected_application.id != application_id)
	{
        $("#applications_list a").removeClass("active");
        $("#application_item_" + application_id).addClass("active");
        
        var application = applications_by_id[application_id];
        
        $("#selected_application_title").html(application.name);

        set_application_url_hash(application_id);
//         $(location).attr("hash", application_id);
        
        //selected_application = application;
        
        $("#applications_list").trigger("application.selected", [application]);
	}
}

function set_application_url_hash(application_id)
{
	$(location).attr("hash", application_id);
}
</script>