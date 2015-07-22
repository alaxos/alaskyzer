
<div class="bugs view">
	<h2><?php echo ___('bug'); ?></h2>
	
	<div class="panel panel-default">
		<div class="panel-heading">
		<?php
		echo $this->Navbars->actionButtons(['buttons_group' => 'view', 'model_id' => $bug->id]);
		?>
		</div>
		<div class="panel-body">
			<dl class="dl-horizontal">
			
				<dt><?php echo __('Status'); ?></dt>
				<dd>
					<?php echo $bug->has('status') ? $this->Html->link($bug->status->name, ['controller' => 'Status', 'action' => 'view', $bug->status->id]) : '' ?>
				</dd>
					
				<dt><?php echo __('Application'); ?></dt>
				<dd>
					<?php echo $bug->has('application') ? $this->Html->link($bug->application->name, ['controller' => 'Applications', 'action' => 'view', $bug->application->id]) : '' ?>
				</dd>
					
				<dt><?php echo __('Server'); ?></dt>
				<dd>
					<?php echo $bug->has('server') ? $this->Html->link($bug->server->name, ['controller' => 'Servers', 'action' => 'view', $bug->server->id]) : '' ?>
				</dd>
					
				<dt><?= ___('name'); ?></dt>
				<dd>
					<?php 
					echo h($bug->name);
					?>
				</dd>
				
				<dt><?= ___('description'); ?></dt>
				<dd>
					<?php 
					echo h($bug->description);
					?>
				</dd>
				
				<dt><?= ___('due_date'); ?></dt>
				<dd>
					<?php 
					echo h($bug->to_display_timezone('due_date'));
					?>
				</dd>
				
				<dt><?= ___('closed'); ?></dt>
				<dd>
					<?php 
					echo h($bug->to_display_timezone('closed'));
					?>
				</dd>
				
			</dl>
			<?php 
			echo $this->element('Alaxos.create_update_infos', ['entity' => $bug], ['plugin' => 'Alaxos']);
			?>
			<div>
			</div>
		</div>
	</div>
</div>
	
