
<div class="tasks form">

    <fieldset>
        <legend><?= ___('copy task') ?></legend>

        <div class="panel panel-default">
            <div class="panel-heading">
            <?php
            echo $this->Navbars->actionButtons(['buttons_group' => 'edit', 'model_id' => $task->id]);
            ?>
            </div>
            <div class="panel-body">

            <?php
            echo $this->AlaxosForm->create($task, ['class' => 'form-horizontal', 'role' => 'form', 'novalidate' => 'novalidate']);

            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('task_category_id', __('task_category_id'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->control('task_category_id', ['options' => $taskCategories, 'empty' => true, 'label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';

            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('status_id', __('status_id'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->control('status_id', ['options' => $status, 'label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';

            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('application_id', __('application_id'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->control('application_id', ['options' => $applications, 'label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';

            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('server_id', __('server_id'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->control('server_id', ['options' => $servers, 'empty' => true, 'label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';

            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('name', __('name'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->control('name', ['label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';

            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('description', __('description'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->control('description', ['label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';

            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('due_date', __('due_date'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->control('due_date', ['empty' => true, 'default' => '', 'label' => false, 'class' => 'form-control']);
            echo '</div>';
            echo '</div>';

            echo '<div class="form-group">';
            echo $this->AlaxosForm->label('closed', __('closed'), ['class' => 'col-sm-2 control-label']);
            echo '<div class="col-sm-5">';
            echo $this->AlaxosForm->control('closed', ['label' => false, 'class' => 'form-control']);
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
