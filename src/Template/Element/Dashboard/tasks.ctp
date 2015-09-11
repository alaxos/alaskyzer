<?php
use Cake\Routing\Router;
?>

<div id="tasks_list" style="display:none;">

    <div style="margin:10px 0px 20px 0px;">
    <?php 
    echo $this->Html->link(__('new task'), '#', ['class' => 'btn btn-default btn-sm', 'id' => 'tasks_list_add_btn']);
    ?>
    </div>
    
    <ul class="nav nav-tabs" role="tablist" id="task_tabs">
        <li role="presentation" id="open_task_tab_link"><a href="#open_tasks_tab"   aria-controls="open_tasks_tab"   role="tab" data-toggle="tab"><?php echo ___('open');?> <span class="badge active_tasks_total"></span></a></li>
        <li role="presentation" id="closed_task_tab_link"><a href="#closed_tasks_tab" aria-controls="closed_tasks_tab" role="tab" data-toggle="tab"><?php echo ___('closed');?> <span class="closed_tasks_total" style="color:#aaa;"></span></a></li>
    </ul>
    
    
    <div class="tab-content" style="margin-top:20px;">
    
        <div role="tabpanel" class="tab-pane fade in active" id="open_tasks_tab">
            
            <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed" id="open_tasks_table" style="display:none;">
            <thead>
            <tr class="sortHeader">
                <th><?php echo ___('created');?></th>
                <th><?php echo ___('name');?></th>
                <th><?php echo ___('due date');?></th>
            </tr>
            </thead>
            <tbody id="open_tasks_rows">
            
            </tbody>
            </table>
            
        </div>
        
        <div role="tabpanel" class="tab-pane fade" id="closed_tasks_tab">
        
            <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed" id="closed_tasks_table" style="display:none;">
            <thead>
            <tr class="sortHeader">
                <th><?php echo ___('created');?></th>
                <th><?php echo ___('name');?></th>
                <th><?php echo ___('closed');?></th>
            </tr>
            </thead>
            <tbody id="closed_tasks_rows">
            
            </tbody>
            </table>
            
        </div>
        
    </div>
    
</div>

<script type="text/javascript">

var tasks_by_id = {};
var selected_task = null;

$(document).ready(function(){

	var newTask = function(){
		if(selected_application != null)
		{
		    window.location = "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'add']);?>?application_id=" + selected_application.id;
		    return false;
		}
    }
    
    $("#tasks_list_add_btn").click(function(e){
        e.preventDefault();
        newTask();
    });

    $(document).on('keydown', null, 'ctrl+n', newTask);
});

function debug_tasks_by_id()
{
	var debug = "";
	$.each(tasks_by_id, function(id, task){
		debug += "tasks_by_id: " + id + " -> " + task.name + "\n";
	});
	window.console.log(debug);
}

function load_tasks_data(application, async)
{
	if(typeof(async) == "undefined"){
        async = true;
    }

	$.ajax({
        url      : "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'find']);?>?application_id=" + application.id + ".json",
        dataType : "json",
        async    : async
    })
    .done(function(data, textStatus, jqXHR){
        tasks = data;

        /*
         * Fill tasks cache
         */
        //tasks_by_id = {};
        $.each(tasks, function(i, task){
        	tasks_by_id[task.id] = task;
        	
        	$("#tasks_list").trigger("task.loaded", [task]);
//         	window.console.log("task " + task.id + " put in cache " + tasks_by_id[task.id]);
        });

//         debug_tasks_by_id();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        Alaxos.manage_ajax_error(jqXHR);
    })
    .always(function(){
        $("#task_details_loader").hide();
    });
}

function fill_tasks_ui_tabs(tasks)
{
	/*
	 * Count open and closed tasks
	 */
	var active_tasks_total = 0;
    var closed_tasks_total = 0;

    $.each(tasks, function(index, task){
        if(task.closed == null && task.abandoned == null){
            active_tasks_total++;
        }else{
            closed_tasks_total++;
        }
    });

    /*
     * If there is at least one open task, the open tab is selected by default
     */
    if(active_tasks_total > 0){
        select_open_tasks_panel();
    }else if(closed_tasks_total > 0){
        select_closed_tasks_panel();
    }
    
    /*
     * If there isn't any task, the tabs are hidden
     */
    if(active_tasks_total > 0 || closed_tasks_total > 0)
    {
    	if(active_tasks_total>0){
            $(".active_tasks_total").html(active_tasks_total);
            $(".active_tasks_total").show();
            
            $("#open_task_tab_link").removeClass("disabled");
            $("#open_task_tab_link").show();
        }else{
            $(".active_tasks_total").hide();
            
            $("#open_task_tab_link").addClass("disabled");
            $("#open_task_tab_link").hide();
        }
        
        if(closed_tasks_total>0){
            $(".closed_tasks_total").html("(" + closed_tasks_total + ")");
            $(".closed_tasks_total").show();
            
            $("#closed_task_tab_link").removeClass("disabled");
            $("#closed_task_tab_link").show();
        }else{
            $(".closed_tasks_total").hide();
            
            $("#closed_task_tab_link").addClass("disabled");
            $("#closed_task_tab_link").hide();
        }
        
        $("#task_tabs").show();
    }
    else
    {
        $("#task_tabs").hide();
    }
}

function fill_tasks_ui_list(tasks, show_application_name)
{
	if(typeof(show_application_name) == "undefined"){
		show_application_name = false;
    }
	
	$("#open_tasks_rows").html("");
    $("#closed_tasks_rows").html("");
    
	var active_tasks_total = 0;
    var closed_tasks_total = 0;
    
    $.each(tasks, function(index, task){
        add_task_row(task, show_application_name);

        if(task.closed == null && task.abandoned == null){
            active_tasks_total++;
        }else{
            closed_tasks_total++;
        }
    });

    if(active_tasks_total>0){
        $("#open_tasks_table").show();
    }else{
        $("#open_tasks_table").hide();
    }
    
    if(closed_tasks_total>0){
        $("#closed_tasks_table").show();
    }else{
        $("#closed_tasks_table").hide();
    }
    
    $("#tasks_list").show();

    $("#tasks_list").trigger("tasks.list_filled", [tasks]);
}

function fill_task_row(task, show_application_name)
{
	if(typeof(show_application_name) == "undefined"){
		show_application_name = false;
    }
    
	var row = $("#task_row_" + task.id);
    if(row.length > 0)
    {
        var task_row_content = get_task_row_content(task, show_application_name);
    	$("#task_row_" + task.id).html(task_row_content);
    }
}

function add_task_row(task, show_application_name)
{
    if(task.closed == null && task.abandoned == null)
    {
    	add_open_task_row(task, show_application_name);
    }
    else
    {
    	add_closed_task_row(task, show_application_name);
    }
}

function get_task_row_content(task, show_application_name)
{
	if(task.closed == null && task.abandoned == null)
    {
    	return get_open_task_row_content(task, show_application_name);
    }
    else
    {
    	return get_closed_task_row_content(task, show_application_name);
    }
}

function get_open_task_row_content(task, show_application_name)
{
	var task_row_content = "";

	task_row_content += "<td>";
	task_row_content += task.created;
	task_row_content += "</td>";

	if(show_application_name && task.application != null && typeof(task.application != "undefined"))
	{
    	task_row_content += "<td>";
    	task_row_content += task.application.name;
    	task_row_content += "</td>";
	}
	
	task_row_content += "<td>";
	task_row_content += task.name;
	task_row_content += "</td>";

	task_row_content += "<td>";
    if(task.due_date != null)
    {
    	task_row_content += task.due_date;
    }
    task_row_content += "</td>";
    
    return task_row_content;
}

function get_closed_task_row_content(task, show_application_name)
{
	var task_row_content = "";

	task_row_content += "<td>";
	task_row_content += task.created;
	task_row_content += "</td>";

	if(show_application_name && task.application != null && typeof(task.application != "undefined"))
	{
    	task_row_content += "<td>";
    	task_row_content += task.application.name;
    	task_row_content += "</td>";
	}

	task_row_content += "<td>";
	task_row_content += task.name;
	task_row_content += "</td>";

	task_row_content += "<td>";
    if(task.closed != null)
    {
    	task_row_content += task.closed;
    }
    task_row_content += "</td>";

    return task_row_content;
}

function add_open_task_row(task, show_application_name)
{
	var task_row = "";

	task_row += "<tr class=\"task-row\" id=\"task_row_" + task.id + "\" data-task-id=\"" + task.id + "\">";
    task_row += get_task_row_content(task, show_application_name);
    task_row += "</tr>";
    
    task_row += "<tr class=\"task-row-details\" id=\"task_row_details_" + task.id + "\" data-task-id=\"" + task.id + "\" style=\"display:none;\">";
    task_row += "<td colspan=\"3\">";
    task_row += "</td>";
    task_row += "</tr>";
    
    $("#open_tasks_rows").append(task_row);

    $("#tasks_list").trigger("task.row_filled", [task]);
}

function add_closed_task_row(task, show_application_name)
{
	var task_row = "";

	task_row += "<tr class=\"task-row\" id=\"task_row_" + task.id + "\" data-task-id=\"" + task.id + "\">";
    task_row += get_task_row_content(task, show_application_name);
    task_row += "</tr>";

    task_row += "<tr class=\"task-row-details\" id=\"task_row_details_" + task.id + "\" data-task-id=\"" + task.id + "\" style=\"display:none;\">";
    task_row += "<td colspan=\"3\">";
    task_row += "</td>";
    task_row += "</tr>";
    
    $("#closed_tasks_rows").append(task_row);

    $("#tasks_list").trigger("task.row_filled", [task]);
}

function select_open_tasks_panel()
{
    if($("#open_task_tab_link.active").length == 0)
    {
        //select_task(null);
    }
    
    $('a[href="#open_tasks_tab"]').tab('show');
}

function select_closed_tasks_panel()
{
    if($("#closed_task_tab_link.active").length == 0)
    {
        //select_task(null);
    }
    
    $('a[href="#closed_tasks_tab"]').tab('show');
}

function register_task_toggle_select(task)
{
	$("#task_row_" + task.id).click(function(){

		if(selected_task == null || selected_task.id != task.id)
		{
			select_task(task);
		}
		else
		{
			unselect_task(task);
		}
    });
}

function select_task(task)
{
	/*
	 * Refresh the task from the cache.
	 * Useful when the task passed by the calling event is still the task that was not loaded yet
	 */
	if(tasks_by_id[task.id] != null)
	{
		task = tasks_by_id[task.id];
	}
	
	selected_task = task;
	$("#tasks_list").trigger("task.selected", [task]);

	set_task_url_hash(task.id);
}

function unselect_task(task)
{
	selected_task = null;
	$("#tasks_list").trigger("task.unselected", [task]);
}

function set_selected_task_row(task)
{
	unset_selected_task_row();
	
	if(task != null)
	{
		   $("#task_row_" + task.id).addClass("info");
	}
}

function unset_selected_task_row()
{
	$(".task-row").removeClass("info");
}
// function clear_task_details(){
// 	$("#task_details").html("");
//     $("#task_details").hide();
// }

function hide_tasks_details()
{
	$("tr.task-row-details").hide();
}

function show_task_details(task, show_name){

	if(typeof(show_name) == "undefined"){
		show_name = false;
    }
    
    var task_details = "";

    if(show_name)
    {
        task_details += "<div class=\"row\">";
    
        task_details += "<div class=\"col-md-1\">";
        task_details += "<span class=\"task_property_name\">";
        task_details += "<?php echo __('name'); ?>";
        task_details += "</span>";
        task_details += "</div>";
    
        task_details += "<div class=\"col-md-11\">";
        task_details += task.name;
        task_details += "</div>";
        
        task_details += "</div>";
    }
    
    /******/
    
    task_details += "<div class=\"row\">";

    task_details += "<div class=\"col-md-1\">";
    task_details += "<span class=\"task_property_name\">";
    task_details += "<?php echo __('description'); ?>";
    task_details += "</span>";
    task_details += "</div>";

    task_details += "<div class=\"col-md-11\">";
//     task_details += "<pre>";
    task_details += task.description;
//     task_details += "</pre>";
    task_details += "</div>";
    
    task_details += "</div>";
    
    /******/
    
    task_details += "<div class=\"row\">";

    task_details += "<div class=\"col-md-1\">";
    task_details += "<span class=\"task_property_name\">";
    task_details += "<?php echo __('category'); ?>";
    task_details += "</span>";
    task_details += "</div>";

    task_details += "<div class=\"col-md-11\">";
    if(typeof(task.task_category) != "undefined" && task.task_category != null)
    {
           task_details += task.task_category.name;
    }
    task_details += "</div>";
    
    task_details += "</div>";

    /******/
    
    task_details += "<div class=\"row\" style=\"margin-top:20px;\">";
    task_details += "<div class=\"col-md-12\">";

    task_details += "<form id=\"postOnClickForm\" action=\"\" method=\"post\"><input type=\"hidden\" id=\"postOnClickFormCsrf\" name=\"_csrfToken\" value=\"<?php echo $this->request->params['_csrfToken']; ?>\" /></form>";

    task_details += "<div class=\"btn-group\">";
    
    if(task.closed == null && task.abandoned == null)
    {
        task_details += '<?php echo $this->Html->link('<span class="glyphicon glyphicon-ok"></span> ' . __('close task'), '#', ['class' => 'btn btn-default btn-sm', 'id' => 'close_task_btn', 'escape' => false]); ?>';
    }
    else
    {
        task_details += '<?php echo $this->Html->link('<span class="glyphicon glyphicon-share"></span> ' . __('open task'), '#', ['class' => 'btn btn-default btn-sm', 'id' => 'open_task_btn', 'escape' => false]); ?>';
    }

    task_details += '<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span> ' . __('edit task'), '#', ['class' => 'btn btn-default btn-sm', 'id' => 'edit_task_btn', 'escape' => false]); ?>';
    task_details += '<?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span> ' . __('delete task'), '#', ['class' => 'btn btn-default btn-sm', 'id' => 'delete_task_btn', 'escape' => false]); ?>';

    task_details += "</div>";
    
    task_details += "</div>";
    task_details += "</div>";
    

    $("#task_row_details_" + task.id + " td").html(task_details);
    $("#task_row_details_" + task.id).show();
    
//     $("#task_details").html(task_details);
//     $("#task_details").show();

//     var task_id = $("#tasks_list tr.info").attr("data-task-id");
    
    $("#close_task_btn").click(function(e){
        e.preventDefault();
        
        $("#postOnClickForm").attr("action", "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'close']);?>/" + task.id);
        $("#postOnClickForm").submit();
    });

    $("#open_task_btn").click(function(e){
        e.preventDefault();
        
        $("#postOnClickForm").attr("action", "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'open']);?>/" + task.id);
        $("#postOnClickForm").submit();
    });

    $("#edit_task_btn").click(function(e){
        e.preventDefault();

        window.location = "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'edit']);?>/" + task.id;
    });

    $("#delete_task_btn").click(function(e){
        e.preventDefault();

        if(confirm("<?php echo ___('do you really want to delete the task ?'); ?>"))
        {
            $("#postOnClickForm").attr("action", "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'delete']);?>/" + task.id);
            $("#postOnClickForm").submit();
        }
    });
}

function set_task_url_hash(task_id)
{
    var hash = $(location).attr("hash").substring(1);
    var ids  = hash.split("_");
    ids[1]   = task_id;
    
    $(location).attr("hash", ids[0] + "_" + ids[1]);
}
</script>