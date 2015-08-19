<?php
namespace App\Model\Table;

use App\Model\Entity\FrameworkVersion;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FrameworkVersions Model
 */
class FrameworkVersionsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('framework_versions');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Alaxos.UserLink');
        $this->belongsTo('Frameworks', [
            'foreignKey' => 'framework_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsToMany('Applications', [
            'through' => 'ApplicationsFrameworks'
            //             'foreignKey' => 'framework_id',
        //             'targetForeignKey' => 'application_id',
        //             'joinTable' => 'applications_frameworks'
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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['framework_id'], 'Frameworks'));
        return $rules;
    }
}
