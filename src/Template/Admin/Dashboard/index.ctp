<?php 
$this->AlaxosHtml->includeAlaxosJs();
?>
<div class="row">
    <div class="col-md-2">
        <?php 
        echo $this->element('Dashboard/applications_list');
        ?>
    </div>
    <div class="col-md-10">
        
        <h2 id="selected_application_title"></h2>
        
        <div class="row">
            <div class="col-md-12">
                <?php 
                echo $this->element('Dashboard/tasks');
                ?>
            </div>
            <div class="col-md-12">
                <?php 
                echo $this->element('Dashboard/task');
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){

	dashboard_init();
	select_from_url_hash();
});

function dashboard_init()
{
	events_init();
	load_applications_data();
}

function events_init()
{
	/*
	 * Applications loaded -> load their tasks 
	 */
	$("#applications_list").on("applications.loaded", function(e, applications){
		$.each(applications, function(i, application){
			load_tasks_data(application);
		});
	});

	/*
	 * Applications loaded -> fill the applications UI list
	 */
	$("#applications_list").on("applications.loaded", function(e, applications){
		fill_applications_ui_list();
	});

    /*
     * Applications UI list filled -> register selection on click
     */
    $("#applications_list").on("applications.ui_list_populated", function(e, applications){
		register_applications_select();
	});

    /*
     * Application selected -> fill tasks tabs + fill tasks rows
     */
    $("#applications_list").on("application.selected", function(e, application){

    	var tasks = [];
        $.each(application.tasks, function(i, task){
            
//             window.console.log("application.selected " + task.id + " " + tasks_by_id[task.id]);

            <?php 
            $loader = $this->Html->image('ajax-loader.gif');
            $loading_text = $loader . ' ' . __('loading...');
            ?>
            if(task != null && tasks_by_id[task.id] != null)
            {
                tasks.push(tasks_by_id[task.id]);
            }
            else
            {
                var tmp_task = {
                        id      : -1, 
                        name    : '<?php echo $loading_text;?>', 
                        created : '<?php echo $loading_text;?>',
                };

                if(task != null)
                {
                    tmp_task.id        = task.id;
                    tmp_task.closed    = task.closed;
                    tmp_task.abandoned = task.abandoned;
                    tmp_task.created   = task.created;
                }
                
                tasks.push(tmp_task);
            }
        });
        
        fill_tasks_ui_tabs(tasks);
		fill_tasks_ui_list(tasks);
	});

    /*
     * Selecting an application clear the tasks details
     */
    $("#applications_list").on("application.selected", function(e, application){

        if(selected_application != null && selected_application.id != application.id)
        {
            //clear_task_details();
        }
    });

    /*
     * Set selected application global variable
     */
    $("#applications_list").on("application.selected", function(e, application){
    	selected_application = application;
    });

    $("#tasks_list").on("task.loaded", function(e, task){
//     	window.console.log("task.loaded " + task.id);
        fill_task_row(task);
//         fill_task_details(task);
    });
        
    /*
     * Task row filled -> register click to select
     */
    $("#tasks_list").on("task.row_filled", function(e, task){
    	register_task_toggle_select(task);
    });

    /*
     * Task selected -> select row, hide other tasks details, show selected task details
     */
    $("#tasks_list").on("task.selected", function(e, task){
    	set_selected_task_row(task);
    	hide_tasks_details();
    	show_task_details(task);
    });

    /*
     * Set the selected task global variable
     */
//     $("#tasks_list").on("task.selected", function(e, task){
//     	selected_task = task;
//     });

    /*
     * Task unselected -> hide details, unselect task row, unset global selected task variable
     */
    $("#tasks_list").on("task.unselected", function(e, task){
    	hide_tasks_details();
    	unset_selected_task_row();
    });
}

function select_from_url_hash()
{
    var hash = window.location.hash.substring(1);
    if(hash != null && hash.length > 0)
    {
        var ids  = hash.split("_");
        
        var application_id = ids[0];
        
        select_application(application_id);
        
        if(ids.length > 1)
        {
            var task_id = ids[1];
            
            if(tasks_by_id[task_id] != null)
            {
                select_task(tasks_by_id[task_id]);
            }
            else
            {
                application = applications_by_id[application_id];
                if(application != null)
                {
                    $.each(application.tasks, function(i, task){
                        if(task.id == task_id){
                            select_task(task);
                        }
                    });
                }
            }
        }
    }
}
</script>