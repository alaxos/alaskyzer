<?php 
$this->AlaxosHtml->includeAlaxosJs();
?>
<div class="row">
    <div class="col-md-3">
        <?php 
        echo $this->element('Dashboard/applications_list');
        ?>
    </div>
    <div class="col-md-9">
        
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

	fill_applications();
	select_from_url_hash();
	
});


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
    	    var task_id        = ids[1];
    	    select_task(task_id);
	    }
	}
}
</script>