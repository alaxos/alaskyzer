<?php
use Cake\Routing\Router;

echo $this->AlaxosHtml->script('selectize/js/standalone/selectize.min', ['block' => true]);
echo $this->AlaxosHtml->css('/js/selectize/css/selectize.bootstrap3', ['block' => true]);
?>

<div class="applications form">

    <fieldset>
        <legend><?= ___('copy application') ?></legend>

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
            echo $this->AlaxosForm->control('name', ['label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';

            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('Frameworks', __('Frameworks'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';

            echo $this->AlaxosForm->hidden('applications_frameworks.0.id');
            echo $this->AlaxosForm->control('applications_frameworks.0.framework_id', ['type' => 'select', 'label' => false, 'options' => $frameworks, 'empty' => true, 'id' => 'frameworks_select']);
            echo $this->Html->image('ajax-loader.gif', ['id' => 'applications_frameworks_0_loader', 'style' => 'display:none;']);

            echo $this->AlaxosForm->control('applications_frameworks.0.framework_version_id', ['type' => 'select', 'options' => $frameworkVersions, 'label' => false, 'empty' => true, 'id' => 'framework_version_select']);

            echo '</div>';
            echo '</div>';

            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('Technologies', __('Technologies'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->control('technologies._ids', ['label' => false, 'options' => $technologies, 'id' => 'technologies_select']);
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

    var framework_version_selectize;

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
        },
        onChange : function(value){
            fill_framework_version_options(value);
        }
    });

    var framework_version_select = $("#framework_version_select").selectize({
        create : true,
        persist : false,
        createOnBlur : true,
        valueField:"id",
        labelField:"name",
        create : function(input){
            return {
                id : "[new]"+input,
                name  : input
            }
        }
    });

    framework_version_selectize = framework_version_select[0].selectize;
//     framework_version_selectize.disable();

    function fill_framework_version_options(framework_id)
    {
        $("#applications_frameworks_0_loader").show();

        framework_version_selectize.disable();
        framework_version_selectize.clearOptions();
        framework_version_selectize.load(function(callback){
            $.ajax({
                url : "<?php echo Router::url(['controller' => 'FrameworkVersions', 'action' => 'get_framework_versions']);?>.json?framework_id=" + framework_id
            })
            .done(function( data, textStatus, jqXHR ){
                if(data.length > 0){
                    callback(data);
                }
            })
            .always(function(){
                framework_version_selectize.enable();
                $("#applications_frameworks_0_loader").hide();
            });
        });
    }

    //fill_framework_version_options($("#frameworks_select").val());
});
</script>
