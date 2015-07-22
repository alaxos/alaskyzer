
<div class="tasks index">
	
	<h2><?= ___('tasks'); ?></h2>
	
	<div class="panel panel-default">
		<div class="panel-heading">
		<?php
		echo $this->Navbars->actionButtons(['paginate_infos' => true, 'select_pagination_limit' => true]);
		?>
		</div>
		<div class="panel-body">
			
			<div class="table-responsive">
			
			<table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
			<thead>
			<tr class="sortHeader">
				<th></th>
				<th><?php echo $this->Paginator->sort('status_id', ___('status_id')); ?></th>
				<th><?php echo $this->Paginator->sort('application_id', ___('application_id')); ?></th>
				<th><?php echo $this->Paginator->sort('server_id', ___('server_id')); ?></th>
				<th><?php echo $this->Paginator->sort('name', ___('name')); ?></th>
				<th><?php echo $this->Paginator->sort('description', ___('description')); ?></th>
				<th style="width:160px;"><?php echo $this->Paginator->sort('due_date', ___('due_date')); ?></th>
				<th style="width:160px;"><?php echo $this->Paginator->sort('closed', ___('closed')); ?></th>
				<th style="width:160px;"><?php echo $this->Paginator->sort('created', ___('created')); ?></th>
				<th style="width:160px;"><?php echo $this->Paginator->sort('modified', ___('modified')); ?></th>
				<th class="actions"></th>
			</tr>
			<tr class="filterHeader">
				<td>
				<?php
				echo $this->AlaxosForm->checkbox('_Tech.selectAll', ['id' => 'TechSelectAll']);
				
				echo $this->AlaxosForm->create($search_entity, array('url' => $this->request->here(false), 'class' => 'form-horizontal', 'role' => 'form', 'novalidate' => 'novalidate'));
				?>
				</td>
				<td>
					<?php
					echo $this->AlaxosForm->filterField('status_id');
					?>
				</td>
				<td>
					<?php
					echo $this->AlaxosForm->filterField('application_id');
					?>
				</td>
				<td>
					<?php
					echo $this->AlaxosForm->filterField('server_id');
					?>
				</td>
				<td>
					<?php
					echo $this->AlaxosForm->filterField('name');
					?>
				</td>
				<td>
					<?php
					echo $this->AlaxosForm->filterField('description');
					?>
				</td>
				<td>
					<?php
					echo $this->AlaxosForm->filterField('due_date');
					?>
				</td>
				<td>
					<?php
					echo $this->AlaxosForm->filterField('closed');
					?>
				</td>
				<td>
					<?php
					echo $this->AlaxosForm->filterField('created');
					?>
				</td>
				<td>
					<?php
					echo $this->AlaxosForm->filterField('modified');
					?>
				</td>
				<td>
					<?php
					echo $this->AlaxosForm->button(___('filter'), ['class' => 'btn btn-default']);
					echo $this->AlaxosForm->end();
					?>
				</td>
			</tr>
			</thead>
			
			<tbody>
			<?php foreach ($tasks as $i => $task): ?>
				<tr>
					<td>
						<?php
						echo $this->AlaxosForm->checkBox('Task.' . $i . '.id', array('value' => $task->id, 'class' => 'model_id'));
						?>
					</td>
					<td>
						<?php echo $task->has('status') ? $this->Html->link($task->status->name, ['controller' => 'Status', 'action' => 'view', $task->status->id]) : ''; ?>
					</td>
					<td>
						<?php echo $task->has('application') ? $this->Html->link($task->application->name, ['controller' => 'Applications', 'action' => 'view', $task->application->id]) : ''; ?>
					</td>
					<td>
						<?php echo $task->has('server') ? $this->Html->link($task->server->name, ['controller' => 'Servers', 'action' => 'view', $task->server->id]) : ''; ?>
					</td>
					<td>
						<?php echo h($task->name) ?>
					</td>
					<td>
						<?php echo h($task->description) ?>
					</td>
					<td>
						<?php echo h($task->to_display_timezone('due_date')); ?>
					</td>
					<td>
						<?php echo h($task->to_display_timezone('closed')); ?>
					</td>
					<td>
						<?php echo h($task->to_display_timezone('created')); ?>
					</td>
					<td>
						<?php echo h($task->to_display_timezone('modified')); ?>
					</td>
					<td class="actions">
						<?php 
// 						echo $this->Navbars->actionButtons(['buttons_group' => 'list_item', 'buttons_list_item' => [['view', 'edit', 'delete']], 'model_id' => $task->id]);
						?>
						
						<?php 
// 						echo $this->Html->link('<span class="glyphicon glyphicon-search"></span> ' . __d('alaxos', 'view'), ['action' => 'view', $task->id], ['class' => 'to_view', 'escape' => false]);
// 						echo ' ';
// 						echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span> ' . __d('alaxos', 'edit'), ['action' => 'edit', $task->id], ['escape' => false]);
// 						echo ' ';
// 						echo $this->Form->postLink('<span class="glyphicon glyphicon-trash"></span> ' . __d('alaxos', 'delete'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'escape' => false]);
						?>
						
						<?php 
						echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', ['action' => 'view', $task->id], ['class' => 'to_view', 'escape' => false]);
						echo '&nbsp;&nbsp;';
						echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', ['action' => 'edit', $task->id], ['escape' => false]);
						echo '&nbsp;&nbsp;';
						echo $this->Form->postLink('<span class="glyphicon glyphicon-trash"></span>', ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'escape' => false]);
						?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			
			</table>
			
			</div>
			
			<?php
			if(isset($tasks) && $tasks->count() > 0)
			{
				echo '<div class="row">';
				echo '<div class="col-md-1">';
				echo $this->AlaxosForm->postActionAllButton(__d('alaxos', 'delete all'), ['action' => 'delete_all'], ['confirm' => __d('alaxos', 'do you really want to delete the selected items ?')]);
				echo '</div>';
				echo '</div>';
			}
			?>
			
			<div class="paging text-center">
				<ul class="pagination pagination-sm">
				<?php
				echo $this->Paginator->prev('< ' . __('previous'));
				echo $this->Paginator->numbers();
				echo $this->Paginator->next(__('next') . ' >');
				?>
				</ul>
			</div>
			
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	Alaxos.start();
});
</script>