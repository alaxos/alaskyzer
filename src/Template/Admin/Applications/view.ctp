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
					foreach($application->applications_frameworks as $application_framework){

					    echo '<span class="tag">';
					    echo $this->Html->link($application_framework->framework->name, array('controller' => 'Frameworks', 'action' => 'view', $application_framework->framework->id));
					    echo ' (';
					    echo $application_framework->framework_version->name;
					    echo ')';
					    echo '</span>';
					}


// 					$c       = new Collection($application->frameworks);
// 					$options = $c->combine('id', 'name')->toArray();
// 					$values  = array_keys($options);

// 					echo $this->AlaxosForm->control('frameworks._ids', ['label' => false, 'options' => $options, 'value' => $values, 'id' => 'frameworks_select']);
					?>
				</dd>

				<dt><?= ___('technologies'); ?></dt>
				<dd>
					<?php
					foreach($application->technologies as $technology){
					    echo '<span class="tag">';
					    echo $this->Html->link($technology->name, array('controller' => 'Technologies', 'action' => 'view', $technology->id));
// 					    echo ' (';
// 					    echo $framework->technology_version->name;
// 					    echo ')';
					    echo '</span>';
					}

// 					$c       = new Collection($application->technologies);
// 					$options = $c->combine('id', 'name')->toArray();
// 					$values  = array_keys($options);

// 					echo $this->AlaxosForm->control('technologies._ids', ['label' => false, 'options' => $options, 'value' => $values, 'id' => 'technologies_select']);
					?>
				</dd>

                <dt><?= ___('close date'); ?></dt>
                <dd>
                    <?php
                    echo $application->to_display_timezone('close_date');
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
//     $("#technologies_select").selectize({
//         create : true,
//         persist : false
//         });
//     $("#technologies_select")[0].selectize.lock();

//     $("#frameworks_select").selectize({
//         create : true,
//         persist : false
//         });
//     $("#frameworks_select")[0].selectize.lock();
});
</script>
