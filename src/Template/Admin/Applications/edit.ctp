<?php 
echo $this->AlaxosHtml->script('selectize/js/standalone/selectize.min', ['block' => true]);
echo $this->AlaxosHtml->css('/js/selectize/css/selectize.bootstrap3', ['block' => true]);
?>

<div class="applications form">
    
    <fieldset>
        <legend><?= ___('edit application') ?></legend>
        
        <div class="panel panel-default">
            <div class="panel-heading">
            <?php
            echo $this->Navbars->actionButtons(['buttons_group' => 'edit', 'model_id' => $application->id]);
            ?>
            </div>
            <div class="panel-body">
            
            <?php
            echo $this->AlaxosForm->create($application, ['class' => 'form-horizontal', 'role' => 'form', 'novalidate' => 'novalidate']);
            
            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('name', __('name'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->input('name', ['label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('Frameworks', __('Frameworks'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->input('frameworks._ids', ['label' => false, 'options' => $frameworks, 'id' => 'frameworks_select']);
            echo '</div>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('Technologies', __('Technologies'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->input('technologies._ids', ['label' => false, 'options' => $technologies, 'id' => 'technologies_select']);
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
    $("#technologies_select").selectize({
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

    $("#frameworks_select").selectize({
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
});
</script>