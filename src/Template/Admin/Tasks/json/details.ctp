<?php
$task_data = [];

foreach($task->visibleProperties() as $field)
{
    if(is_a($task->{$field}, 'Cake\I18n\Time'))
    {
        $time = $task->to_display_timezone($field);
    
        if(isset($time) && is_a($time, 'Cake\I18n\Time'))
        {
            $task_data[$field] = $time->i18nFormat();
        }
        else
        {
            $task_data[$field] = $time;
        }
    }
    elseif(in_array($field, ['description']))
    {
        $task_data[$field] = $this->AlaxosHtml->formatText($task->{$field}, ['encode_email' => false]);
    }
    else
    {
        $task_data[$field] = $task->{$field};
    }
}

echo json_encode($task_data);