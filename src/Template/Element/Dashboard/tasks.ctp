<?php
use Cake\Routing\Router;
?>

<div id="tasks_list" style="display:none;">

    <div class="text-right" style="margin-bottom:10px;">
    <?php 
    echo $this->Html->link(__('new task'), '#', ['class' => 'btn btn-default btn-sm', 'id' => 'tasks_list_add_btn']);
    ?>
    </div>
    
    <ul class="nav nav-tabs" role="tablist" id="task_tabs">
        <li role="presentation" id="open_task_tab_link"><a href="#open_tasks_tab"   aria-controls="open_tasks_tab"   role="tab" data-toggle="tab"><?php echo ___('open');?> <span class="badge active_tasks_total"></span></a></li>
        <li role="presentation" id="closed_task_tab_link"><a href="#closed_tasks_tab" aria-controls="closed_tasks_tab" role="tab" data-toggle="tab"><?php echo ___('closed');?> <span class="badge closed_tasks_total"></span></a></li>
    </ul>
    
    
    <div class="tab-content" style="margin-top:20px;">
    
        <div role="tabpanel" class="tab-pane fade in active" id="open_tasks_tab">
            
            <ul class="list-group" id="open_tasks">
            
            </ul>
            
        </div>
        
        <div role="tabpanel" class="tab-pane fade" id="closed_tasks_tab">
        
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
		
// 	    if($("#applications_list a.active").length > 0)
// 	    {
//	    	  window.location = "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'add']);?>?application_id=" + $("#applications_list a.active").attr("data-application-id");
// 	    }
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
	$("#open_tasks").html("");
	$("#closed_tasks").html("");
	$("#task_details").html("").hide();
}

function fill_tasks(tasks)
{
	var active_tasks_total = 0;
    var closed_tasks_total = 0;
    
	$.each(tasks, function(index, task){
		fill_task_row(task);

		if(task.closed == null && task.abandoned == null){
			active_tasks_total++;
		}else{
			closed_tasks_total++;
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
    }else{
        $(".active_tasks_total").hide();
        $("#open_task_tab_link").addClass("disabled");
    }
    
    if(closed_tasks_total>0){
        $(".closed_tasks_total").html(closed_tasks_total);
        $(".closed_tasks_total").show();
        $("#closed_task_tab_link").removeClass("disabled");
    }else{
        $(".closed_tasks_total").hide();
        $("#closed_task_tab_link").addClass("disabled");
    }

    $("#tasks_list").show();
}

function fill_task_row(task)
{
	var task_item = "";

	if(task.closed == null && task.abandoned == null)
    {
	    task_item += "<a class=\"list-group-item task-line\" data-task-id=\"" + task.id + "\" id=\"task_" + task.id + "\">";
	    
        task_item += "<div class=\"row\">";

        task_item += "<div class=\"col-md-2\">";
        task_item += task.created;
        task_item += "</div>";
        
        task_item += "<div class=\"col-md-8\">";
        task_item += task.name;
        task_item += "</div>";
        
        task_item += "<div class=\"col-md-2\">";
        if(task.due_date != null)
        {
            task_item += task.due_date;
        }
        task_item += "</div>";
        
        task_item += "</div>";
        
        task_item += "</a>";
        
        $("#open_tasks").append(task_item);
    }
    else
    {
    	task_item += "<a class=\"list-group-item task-line\" data-task-id=\"" + task.id + "\" id=\"task_" + task.id + "\">";

        task_item += "<div class=\"row\">";

        task_item += "<div class=\"col-md-2\">";
        task_item += task.created;
        task_item += "</div>";
        
        task_item += "<div class=\"col-md-8\">";
        task_item += task.name;
        task_item += "</div>";
        
        task_item += "<div class=\"col-md-2\">";
        if(task.closed != null)
        {
            task_item += "<?php echo __('closed');?>: " + task.closed;
        }
        task_item += "</div>";
        
        task_item += "</div>";
        
    	task_item += "</a>";
    	
    	$("#closed_tasks").append(task_item);
    }

	$("#task_" + task.id).click(function(){
		select_task($(this).attr("data-task-id"));
	});
}

var tasks_by_id = {};

function get_task_details(task_id)
{
	$("#task_details_loader").show();

    if(typeof(tasks_by_id[task_id]) == "undefined")
    {
    	var task = null;
    	
    	$.ajax({
    		url      : "<?php echo Router::url(['controller' => 'Tasks', 'action' => 'details']);?>/" + task_id + ".json",
    		dataType : "json",
    		async: false
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
	$(".task-line").removeClass("active");

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
    
    	if(task.closed == null && task.abandoned == null)
        {
    		select_open_tasks_panel();
        }
    	else
    	{
    		select_closed_tasks_panel();
    	}

    	$("#task_" + task_id).addClass("active");
    	
    	show_task_details(task);
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
    task_details += task.description;
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
        
    if(task.closed == null && task.abandoned == null)
    {
    	task_details += '<?php echo $this->Html->link(__('close task'), '#', ['class' => 'btn btn-default btn-sm', 'id' => 'close_task_btn']); ?>';
    }
    else
    {
    	task_details += '<?php echo $this->Html->link(__('open task'), '#', ['class' => 'btn btn-default btn-sm', 'id' => 'open_task_btn']); ?>';
    }
    
    task_details += "</div>";
    task_details += "</div>";
    
    
    $("#task_details").html(task_details);
    $("#task_details").show();

    var task_id = $("#tasks_list .list-group .active").attr("data-task-id");
    
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
}
</script>