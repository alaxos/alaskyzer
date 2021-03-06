<?php
namespace App\Model\Table;

use App\Model\Entity\Task;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\I18n\Time;
use Cake\Core\Configure;

/**
 * Tasks Model
 */
class TasksTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->setTable('tasks');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Alaxos.UserLink');
        $this->addBehavior('Alaxos.Timezoned');

        $this->belongsTo('TaskCategories', [
            'foreignKey' => 'task_category_id'
        ]);

        $this->belongsTo('Applications', [
            'foreignKey' => 'application_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Servers', [
            'foreignKey' => 'server_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->allowEmpty('description')

            ->add('due_date', 'valid', ['rule' => 'date'])
            ->allowEmpty('due_date')

            ->add('closed', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('closed')
            ->add('created_by', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('created_by')
            ->add('modified_by', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('modified_by');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['task_category_id'], 'TaskCategories'));
        $rules->add($rules->existsIn(['application_id'], 'Applications'));
        $rules->add($rules->existsIn(['server_id'], 'Servers'));
        return $rules;
    }


    public function close($id, $datetime = null)
    {
        $task = $this->get($id);

        if(!isset($task->closed))
        {
            $this->patchEntity($task, ['closed' => new Time()]);

            return $this->save($task);
        }
        else
        {
            return false;
        }
    }

    public function open($id, $datetime = null)
    {
        $task = $this->get($id);

        if(isset($task->closed) || isset($task->abandoned))
        {
            $this->patchEntity($task, ['closed' => null, 'abandoned' => null]);

            return $this->save($task);
        }
        else
        {
            return false;
        }
    }
}
