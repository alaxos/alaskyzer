
<div class="frameworkVersions view">
	<h2><?php echo ___('framework version'); ?></h2>
	
	<div class="panel panel-default">
		<div class="panel-heading">
		<?php
		echo $this->Navbars->actionButtons(['buttons_group' => 'view', 'model_id' => $frameworkVersion->id]);
		?>
		</div>
		<div class="panel-body">
			<dl class="dl-horizontal">
			
				<dt><?php echo __('Framework'); ?></dt>
				<dd>
					<?php echo $frameworkVersion->has('framework') ? $this->Html->link($frameworkVersion->framework->name, ['controller' => 'Frameworks', 'action' => 'view', $frameworkVersion->framework->id]) : '' ?>
				</dd>
					
				<dt><?= ___('name'); ?></dt>
				<dd>
					<?php 
					echo h($frameworkVersion->name);
					?>
				</dd>
				
			</dl>
			<?php 
			echo $this->element('Alaxos.create_update_infos', ['entity' => $frameworkVersion], ['plugin' => 'Alaxos']);
			?>
			<div>
			</div>
		</div>
	</div>
</div>
	
