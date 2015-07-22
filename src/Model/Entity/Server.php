<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Alaxos\Model\Entity\TimezonedTrait;

/**
 * Server Entity.
 */
class Server extends Entity
{
	use TimezonedTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'created_by' => true,
        'modified_by' => true,
        'bugs' => true,
        'instances' => true,
        'tasks' => true,
    ];
}
