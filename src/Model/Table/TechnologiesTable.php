<?php
namespace App\Model\Table;

use App\Model\Entity\Technology;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Alaxos\Lib\StringTool;

/**
 * Technologies Model
 */
class TechnologiesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->setTable('technologies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Alaxos.UserLink');
        $this->belongsToMany('Applications', [
            'foreignKey' => 'technology_id',
            'targetForeignKey' => 'application_id',
            'joinTable' => 'applications_technologies'
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
            ->add('created_by', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('created_by')
            ->add('modified_by', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('modified_by');

        return $validator;
    }

    public function ensureEntityExists($name)
    {
        if(StringTool::start_with($name, '[new]')){
            $name = StringTool::remove_leading($name, '[new]');
        }

        $search = [
            'name'         => $name
        ];

        return $this->findOrCreate($search);
    }
}
