<?php
$parser = new ParsedownExtra();
$parser->setBreaksEnabled(true);

$tasks_data = [];

foreach($tasks as $task)
{
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
            $task_data[$field] = $parser->text($task->{$field});
        }
        else
        {
            $task_data[$field] = $task->{$field};
        }
    }
    
    $tasks_data[] = $task_data;
}

echo json_encode($tasks_data);