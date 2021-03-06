<?php
namespace App\Model\Table;

use App\Model\Entity\FrameworkVersion;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Alaxos\Lib\StringTool;

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
        $this->setTable('framework_versions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Alaxos.UserLink');

        $this->hasMany('ApplicationsFrameworks', [
            'foreignKey' => 'framework_version_id',
            'dependant'  => true
        ]);

        $this->belongsTo('Frameworks', [
            'foreignKey' => 'framework_id',
            'joinType' => 'INNER'
        ]);
//         $this->belongsToMany('Applications', [
//             'through' => 'ApplicationsFrameworks'
//             //             'foreignKey' => 'framework_id',
//         //             'targetForeignKey' => 'application_id',
//         //             'joinTable' => 'applications_frameworks'
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
        $rules->add($rules->existsIn(['framework_id'], 'Frameworks'));
        return $rules;
    }

    public function ensureEntityExists($framework_id, $name)
    {
        if(StringTool::start_with($name, '[new]')){
            $name = StringTool::remove_leading($name, '[new]');
        }

        $search = [
            'framework_id' => $framework_id,
            'name'         => $name
        ];

        return $this->findOrCreate($search);
    }

    public function updateNaturalSortValues($framework_id)
    {
        $framework_versions = $this->find('all')->where(['framework_id' => $framework_id]);

        if(!empty($framework_versions))
        {
            $names_sorted = [];
            $names        = [];
            foreach($framework_versions as $framework_version)
            {
                $names_sorted[$framework_version->name] = $framework_version;
                $names[] = $framework_version->name;
            }

            natsort($names);

            $sort = 0;
            foreach($names as $name)
            {
                $framework_version = $names_sorted[$name];
                $framework_version->sort = $sort;

                if(!$this->save($framework_version)){
                    return false;
                }

                $sort += 10;
            }
        }

        return true;
    }
}
