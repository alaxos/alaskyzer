
<div class="tasks view">
	<h2><?php echo ___('task'); ?></h2>
	
	<div class="panel panel-default">
		<div class="panel-heading">
		<?php
		echo $this->Navbars->actionButtons(['buttons_group' => 'view', 'model_id' => $task->id]);
		?>
		</div>
		<div class="panel-body">
			<dl class="dl-horizontal">
			
				<dt><?php echo __('Status'); ?></dt>
				<dd>
					<?php echo $task->has('status') ? $this->Html->link($task->status->name, ['controller' => 'Status', 'action' => 'view', $task->status->id]) : '' ?>
				</dd>
					
				<dt><?php echo __('Application'); ?></dt>
				<dd>
					<?php echo $task->has('application') ? $this->Html->link($task->application->name, ['controller' => 'Applications', 'action' => 'view', $task->application->id]) : '' ?>
				</dd>
					
				<dt><?php echo __('Server'); ?></dt>
				<dd>
					<?php echo $task->has('server') ? $this->Html->link($task->server->name, ['controller' => 'Servers', 'action' => 'view', $task->server->id]) : '' ?>
				</dd>
					
				<dt><?= ___('name'); ?></dt>
				<dd>
					<?php 
					echo h($task->name);
					?>
				</dd>
				
				<dt><?= ___('description'); ?></dt>
				<dd>
					<?php 
					echo h($task->description);
					?>
				</dd>
				
				<dt><?= ___('due_date'); ?></dt>
				<dd>
					<?php 
					echo h($task->to_display_timezone('due_date'));
					?>
				</dd>
				
				<dt><?= ___('closed'); ?></dt>
				<dd>
					<?php 
					echo h($task->to_display_timezone('closed'));
					?>
				</dd>
				
			</dl>
			<?php 
			echo $this->element('Alaxos.create_update_infos', ['entity' => $task], ['plugin' => 'Alaxos']);
			?>
			<div>
			</div>
		</div>
	</div>
</div>
	
