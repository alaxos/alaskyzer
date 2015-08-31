<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ApplicationsFrameworksFixture
 *
 */
class ApplicationsFrameworksFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'application_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'framework_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'framework_version_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'application_id' => ['type' => 'index', 'columns' => ['application_id'], 'length' => []],
            'framework_id' => ['type' => 'index', 'columns' => ['framework_id'], 'length' => []],
            'framework_version_id' => ['type' => 'index', 'columns' => ['framework_version_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'applications_frameworks_ibfk_3' => ['type' => 'foreign', 'columns' => ['framework_version_id'], 'references' => ['framework_versions', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'applications_frameworks_ibfk_1' => ['type' => 'foreign', 'columns' => ['application_id'], 'references' => ['applications', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'applications_frameworks_ibfk_2' => ['type' => 'foreign', 'columns' => ['framework_id'], 'references' => ['frameworks', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'application_id' => 1,
            'framework_id' => 1,
            'framework_version_id' => 1,
            'created' => '2015-08-18 08:15:09',
            'created_by' => 1,
            'modified' => '2015-08-18 08:15:09',
            'modified_by' => 1
        ],
    ];
}
