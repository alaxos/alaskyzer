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
            
            <ul class="list-group" id="open_tasks">
            
            </ul>
            
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
            
            <ul class="list-group" id="closed_tasks">
            
            </ul>
        </div>
        
    </div>
    
</div>

<script type="text/javascript">
$(document).ready(function(){

	$('#task_tabs a').click(function (e) {
	    e.preventDefault();

	    if($(this).attr("href") == "#open_tasks_tab")
	    {
	    	select_open_tasks_panel();
	    }
	    else
	    {
	    	select_closed_tasks_panel();
	    }

        //$(this).tab('show');
	})
	
	var newTask = function(){
        window.location = "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'add']);?>?application_id=" + $("#applications_list a.active").attr("data-application-id");
        return false;
    }
    
	$("#tasks_list_add_btn").click(function(e){
		e.preventDefault();
		newTask();
	});
    
	$(document).on('keydown', null, 'ctrl+n', newTask);
});



function select_open_tasks_panel()
{
	if($("#open_task_tab_link.active").length == 0)
	{
		select_task(null);
	}
	
	$('a[href="#open_tasks_tab"]').tab('show');
}

function select_closed_tasks_panel()
{
	if($("#closed_task_tab_link.active").length == 0)
	{
		select_task(null);
	}
	
	$('a[href="#closed_tasks_tab"]').tab('show');
}

function clear_tasks()
{
	$("#open_tasks_rows").html("");
	$("#closed_tasks_rows").html("");
	$("#task_details").html("").hide();
}

function fill_tasks(tasks, load_details)
{
	if(typeof(load_details) == "undefined"){
		load_details = false;
    }
    
	var active_tasks_total = 0;
    var closed_tasks_total = 0;
    
	$.each(tasks, function(index, task){
		fill_task_row(task);

		if(task.closed == null && task.abandoned == null){
			active_tasks_total++;
		}else{
			closed_tasks_total++;
		}

		if(load_details){
			get_task_details(task.id, true);
		}
	});

    if(active_tasks_total > 0){
        select_open_tasks_panel();
    }else if(closed_tasks_total > 0){
    	select_closed_tasks_panel();
    }

    if(active_tasks_total>0){
        $(".active_tasks_total").html(active_tasks_total);
        $(".active_tasks_total").show();
        $("#open_task_tab_link").removeClass("disabled");
        $("#open_tasks_table").show();
        $("#open_task_tab_link").show();
    }else{
        $(".active_tasks_total").hide();
        $("#open_task_tab_link").addClass("disabled");
        $("#open_tasks_table").hide();
        $("#open_task_tab_link").hide();
    }
    
    if(closed_tasks_total>0){
        $(".closed_tasks_total").html("(" + closed_tasks_total + ")");
        $(".closed_tasks_total").show();
        $("#closed_task_tab_link").removeClass("disabled");
        $("#closed_tasks_table").show();
        $("#closed_task_tab_link").show();
    }else{
        $(".closed_tasks_total").hide();
        $("#closed_task_tab_link").addClass("disabled");
        $("#closed_tasks_table").hide();
        $("#closed_task_tab_link").hide();
    }

    if(active_tasks_total > 0 || closed_tasks_total > 0)
    {
        $("#task_tabs").show();
    }
    else
    {
    	$("#task_tabs").hide();
    }
    
    $("#tasks_list").show();
}

function fill_task_row(task)
{
	var task_row = "";

	if(task.closed == null && task.abandoned == null)
    {
        task_row += "<tr class=\"task-row\" id=\"task_row_" + task.id + "\" data-task-id=\"" + task.id + "\">";
        
        task_row += "<td>";
        task_row += task.created;
        task_row += "</td>";

        task_row += "<td>";
        task_row += task.name;
        task_row += "</td>";

        task_row += "<td>";
        if(task.due_date != null)
        {
        	task_row += task.due_date;
        }
        task_row += "</td>";
        
        task_row += "</tr>";

        $("#open_tasks_rows").append(task_row);
    }
    else
    {
        task_row += "<tr class=\"task-row\" id=\"task_row_" + task.id + "\" data-task-id=\"" + task.id + "\">";
        
        task_row += "<td>";
        task_row += task.created;
        task_row += "</td>";

        task_row += "<td>";
        task_row += task.name;
        task_row += "</td>";

        task_row += "<td>";
        if(task.closed != null)
        {
        	task_row += task.closed;
        }
        task_row += "</td>";
        
        task_row += "</tr>";

        $("#closed_tasks_rows").append(task_row);
    }

	$("#task_row_" + task.id).click(function(){
		select_task($(this).attr("data-task-id"));
	});
}

var tasks_by_id = {};

function get_task_details(task_id, async)
{
	if(typeof(async) == "undefined"){
		async = false;
    }
    
	$("#task_details_loader").show();

    if(typeof(tasks_by_id[task_id]) == "undefined")
    {
    	var task = null;
    	
    	$.ajax({
    		url      : "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'details']);?>/" + task_id + ".json",
    		dataType : "json",
    		async    : async
    	})
    	.done(function(data, textStatus, jqXHR){
    		task = data;
    		tasks_by_id[task.id] = task;
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
    		Alaxos.manage_ajax_error(jqXHR);
    	})
    	.always(function(){
    		$("#task_details_loader").hide();
    	});
    }

    $("#task_details_loader").hide();
    
	return tasks_by_id[task_id];
}

function select_task(task_id)
{
	$(".task-row").removeClass("info");

	if(task_id != null)
	{
	    $("#task_details").html("").hide();
    	
    	/*
    	 * Update url hash
    	 */
    	var hash = $(location).attr("hash").substring(1);
    	var ids  = hash.split("_");
    	ids[1] = task_id;
    	$(location).attr("hash", ids[0] + "_" + ids[1]);
    
    	/*
    	 * Show task details
    	 */
    	var task = get_task_details(task_id);

        if(task != null)
        {
            if(task.closed == null && task.abandoned == null)
            {
                select_open_tasks_panel();
            }
            else
            {
                select_closed_tasks_panel();
            }
            
            $("#task_row_" + task_id).addClass("info");
            
            show_task_details(task);
        }
        else
        {
            Alaxos.manage_ajax_error("<?php echo __('task not found'); ?>");
        }
	}
	else
	{
		clear_task_details();
	}
}

function clear_task_details()
{
	$("#task_details").html("").hide();
}

function show_task_details(task){

	var task_details = "";

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

    /******/
    
    task_details += "<div class=\"row\">";

    task_details += "<div class=\"col-md-1\">";
    task_details += "<span class=\"task_property_name\">";
    task_details += "<?php echo __('description'); ?>";
    task_details += "</span>";
    task_details += "</div>";

    task_details += "<div class=\"col-md-11\">";
    task_details += "<pre>";
    task_details += task.description;
    task_details += "</pre>";
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
    
    
    $("#task_details").html(task_details);
    $("#task_details").show();

    var task_id = $("#tasks_list tr.info").attr("data-task-id");
    
    $("#close_task_btn").click(function(e){
        e.preventDefault();
        
        $("#postOnClickForm").attr("action", "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'close']);?>/" + task_id);
        $("#postOnClickForm").submit();
    });

    $("#open_task_btn").click(function(e){
        e.preventDefault();
        
        $("#postOnClickForm").attr("action", "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'open']);?>/" + task_id);
        $("#postOnClickForm").submit();
    });

    $("#edit_task_btn").click(function(e){
        e.preventDefault();

        window.location = "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'edit']);?>/" + task_id;
    });

    $("#delete_task_btn").click(function(e){
        e.preventDefault();

        if(confirm("<?php echo ___('do you really want to delete the task ?'); ?>"))
        {
            $("#postOnClickForm").attr("action", "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'delete']);?>/" + task_id);
            $("#postOnClickForm").submit();
        }
    });
}
</script>