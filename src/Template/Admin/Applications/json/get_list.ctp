<?php
$json_array = [];

foreach($applications as $application)
{
    $application_data = [];
    
    foreach($application->visibleProperties() as $field)
    {
        if(is_a($application->{$field}, 'Cake\I18n\Time'))
        {
            $time = $application->to_display_timezone($field);
            
            if(isset($time) && is_a($time, 'Cake\I18n\Time'))
            {
                $application_data[$field] = $time->i18nFormat();
            }
            else
            {
                $application_data[$field] = $time;
            }
        }
        elseif(in_array($field, ['description']))
        {
            $application_data[$field] = $this->AlaxosHtml->formatText($application->{$field});
        }
        else
        {
            $application_data[$field] = $application->{$field};
        }
    }
    
    $tasks_data = [];
    foreach($application->tasks as $task)
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
                $task_data[$field] = $this->AlaxosHtml->formatText($task->{$field});
            }
            else
            {
                $task_data[$field] = $task->{$field};
            }
        }
        
        $tasks_data[] = $task_data;
    }
    $application_data['tasks'] = $tasks_data;
    
    $json_array[] = $application_data;
    
//     $application->created = $application->to_display_timezone();
}


echo json_encode($json_array);