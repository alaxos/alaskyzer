<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'role_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'username' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'firstname' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'lastname' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'external_uid' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'targeted_uid' => ['type' => 'string', 'length' => 256, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'enabled' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'last_login_date' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'role_id' => ['type' => 'index', 'columns' => ['role_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'external_uid' => ['type' => 'unique', 'columns' => ['external_uid'], 'length' => []],
            'email' => ['type' => 'unique', 'columns' => ['email'], 'length' => []],
            'users_ibfk_1' => ['type' => 'foreign', 'columns' => ['role_id'], 'references' => ['roles', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'id'                => 1,
            'role_id'           => 1,
            'username'          => 'john@unige.ch',
            'firstname'         => 'John',
            'lastname'          => 'Doe',
            'email'             => 'John.Doe@unige.ch',
            'external_uid'      => '12345@unige.ch',
            'targeted_uid'      => 'https://idp.unige.ch/idp/shibboleth!https://rodn.unige.ch/shibboleth!n1OOJitVhtmQ8dH54LhvWQUc/f8=',
            'enabled'           => true,
            'last_login_date'   => '2017-01-01 00:00:00',
            'created'           => '2016-12-01 00:00:00',
            'created_by'        => null,
            'modified'          => '2017-01-01 00:00:00',
            'modified_by'        => null
        ],
        [
            'id'                => 2,
            'role_id'           => 2,
            'username'          => 'bob@unige.ch',
            'firstname'         => 'Bob',
            'lastname'          => 'Morane',
            'email'             => 'Bob.Morane@unige.ch',
            'external_uid'      => '7845@unige.ch',
            'targeted_uid'      => 'https://idp.unige.ch/idp/shibboleth!https://rodn.unige.ch/shibboleth!n1OOJiwuerfzfhuebLhvWQUc/f8=',
            'enabled'           => true,
            'last_login_date'   => '2017-03-01 00:00:00',
            'created'           => '2016-12-01 00:00:00',
            'created_by'        => null,
            'modified'          => '2017-03-01 00:00:00',
            'modified_by'        => null
        ],
        [
            'id'                => 3,
            'role_id'           => 3,
            'username'          => 'alice@unige.ch',
            'firstname'         => 'Alice',
            'lastname'          => 'Wonderland',
            'email'             => 'Alice.Wonderland@unige.ch',
            'external_uid'      => '13465@unige.ch',
            'targeted_uid'      => 'https://idp.unige.ch/idp/shibboleth!https://rodn.unige.ch/shibboleth!niol8olk90fhuebLhvWQUc/f8=',
            'enabled'           => true,
            'last_login_date'   => '2017-02-17 00:00:00',
            'created'           => '2016-12-01 00:00:00',
            'created_by'        => null,
            'modified'          => '2017-02-17 00:00:00',
            'modified_by'        => null
        ],
    ];
}
