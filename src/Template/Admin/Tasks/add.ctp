<?php 
use Cake\Routing\Router;
echo $this->AlaxosHtml->script('selectize/js/standalone/selectize.min', ['block' => true]);
echo $this->AlaxosHtml->css('/js/selectize/css/selectize.bootstrap3', ['block' => true]);
?>

<div class="tasks form">
    
    <fieldset>
        <legend><?= ___('add task') ?></legend>
        
        <div class="panel panel-default">
            <div class="panel-heading">
            <?php
            echo $this->Navbars->actionButtons(['buttons_group' => 'add']);
            ?>
            </div>
            <div class="panel-body">
            
            <?php
            echo $this->AlaxosForm->create($task, ['class' => 'form-horizontal', 'role' => 'form', 'novalidate' => 'novalidate']);
            
            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('task_category_id', __('task_category_id'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->input('task_category_id', ['options' => $taskCategories, 'empty' => true, 'label' => false, 'class' => 'form-control', 'id' => 'task_category_select']);
            echo '</div>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('application_id', __('application_id'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->input('application_id', ['options' => $applications, 'empty' => true, 'label' => false, 'class' => 'form-control', 'id' => 'application_select']);
            echo '</div>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('server_id', __('server_id'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->input('server_id', ['options' => $servers, 'empty' => true, 'label' => false, 'class' => 'form-control', 'id' => 'server_select']);
            echo '</div>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('name', __('name'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->input('name', ['label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('description', __('description'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->input('description', ['label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('due_date', __('due_date'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->input('due_date', ['empty' => true, 'default' => '', 'label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo '<div class="col-sm-offset-2 col-sm-5">';
            echo $this->AlaxosForm->button(___('submit'), ['class' => 'btn btn-default']);
            echo '</div>';
            echo '</div>';
            
            echo $this->AlaxosForm->end(); 
            ?>
            </div>
        </div>
        
    </fieldset>
    
</div>

<script type="text/javascript">
$(document).ready(function() {

    $("#task_category_select").selectize();
    $("#server_select").selectize();
    
    $("#application_select").selectize({
        create : true,
        persist : false,
        createOnBlur : true,
        create : function(input){
            return {
                value : "[new]"+input,
                text  : input
            }
        }
    });

    $("#status_select").selectize();
    
});
</script>