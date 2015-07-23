<?php 
use Cake\Collection\Collection;

echo $this->AlaxosHtml->script('selectize/js/standalone/selectize.min', ['block' => true]);
echo $this->AlaxosHtml->css('/js/selectize/css/selectize.bootstrap3', ['block' => true]);
?>

<div class="applications view">
	<h2><?php echo ___('application'); ?></h2>
	
	<div class="panel panel-default">
		<div class="panel-heading">
		<?php
		echo $this->Navbars->actionButtons(['buttons_group' => 'view', 'model_id' => $application->id]);
		?>
		</div>
		<div class="panel-body">
			<dl class="dl-horizontal">
			
				<dt><?= ___('name'); ?></dt>
				<dd>
					<?php 
					echo h($application->name);
					?>
				</dd>
				
				<dt><?= ___('frameworks'); ?></dt>
				<dd>
					<?php 
					$c       = new Collection($application->frameworks);
					$options = $c->combine('id', 'name')->toArray();
					$values  = array_keys($options);
					
					echo $this->AlaxosForm->input('frameworks._ids', ['label' => false, 'options' => $options, 'value' => $values, 'id' => 'frameworks_select']);
					?>
				</dd>
				
				<dt><?= ___('technologies'); ?></dt>
				<dd>
					<?php 
					$c       = new Collection($application->technologies);
					$options = $c->combine('id', 'name')->toArray();
					$values  = array_keys($options);
					
					echo $this->AlaxosForm->input('technologies._ids', ['label' => false, 'options' => $options, 'value' => $values, 'id' => 'technologies_select']);
					?>
				</dd>
			</dl>
			<?php 
			echo $this->element('Alaxos.create_update_infos', ['entity' => $application], ['plugin' => 'Alaxos']);
			?>
			<div>
			</div>
		</div>
	</div>
</div>
	

<script type="text/javascript">
$(document).ready(function() {
    $("#technologies_select").selectize({
        create : true,
        persist : false
        });
    $("#technologies_select")[0].selectize.lock();

    $("#frameworks_select").selectize({
        create : true,
        persist : false
        });
    $("#frameworks_select")[0].selectize.lock();
});
</script>