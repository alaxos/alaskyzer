<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Alaxos\Model\Entity\TimezonedTrait;

/**
 * Task Entity.
 */
class Task extends Entity
{
	use TimezonedTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'status_id' => true,
        'application_id' => true,
        'server_id' => true,
        'name' => true,
        'description' => true,
        'due_date' => true,
        'closed' => true,
        'created_by' => true,
        'modified_by' => true,
        'status' => true,
        'application' => true,
        'server' => true,
    ];
}
