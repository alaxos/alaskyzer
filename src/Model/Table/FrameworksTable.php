<?php
namespace App\Model\Table;

use App\Model\Entity\Framework;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Alaxos\Lib\StringTool;

/**
 * Frameworks Model
 */
class FrameworksTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('frameworks');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Alaxos.UserLink');
        
        $this->hasMany('ApplicationsFrameworks', [
            'foreignKey' => 'framework_id',
            'dependant'  => true
        ]);
        
//         $this->belongsToMany('Applications', [
//             'through' => 'ApplicationsFrameworks'
// //             'foreignKey' => 'framework_id',
// //             'targetForeignKey' => 'application_id',
// //             'joinTable' => 'applications_frameworks'
//         ]);
//         $this->hasOne('FrameworkVersions', [
//             'foreignKey' => 'framework_id',
//         ]);
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
        $rules->add($rules->isUnique(['name']));
        return $rules;
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
