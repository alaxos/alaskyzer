<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Alaxos\Model\Entity\TimezonedTrait;

/**
 * User Entity.
 */
class User extends Entity
{
	use TimezonedTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'role_id' => true,
        'username' => true,
        'firstname' => true,
        'lastname' => true,
        'email' => true,
        'password' => true,
        'external_uid' => true,
        'enabled' => true,
        'created_by' => true,
        'modified_by' => true,
        'role' => true,
    ];
    
    public function _getFullname()
    {
        return $this->_properties['firstname'] . ' ' . $this->_properties['lastname'];
    }
}
