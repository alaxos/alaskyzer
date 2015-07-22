<?php
namespace App\Model\Table;

use App\Model\Entity\Application;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Applications Model
 */
class ApplicationsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('applications');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Alaxos.UserLink');
        $this->hasMany('Bugs', [
            'foreignKey' => 'application_id'
        ]);
        $this->hasMany('Instances', [
            'foreignKey' => 'application_id'
        ]);
        $this->hasMany('Tasks', [
            'foreignKey' => 'application_id'
        ]);
        $this->belongsToMany('Frameworks', [
            'foreignKey' => 'application_id',
            'targetForeignKey' => 'framework_id',
            'joinTable' => 'applications_frameworks'
        ]);
        $this->belongsToMany('Technologies', [
            'foreignKey' => 'application_id',
            'targetForeignKey' => 'technology_id',
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
}
