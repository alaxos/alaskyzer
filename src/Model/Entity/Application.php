<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Alaxos\Model\Entity\TimezonedTrait;

/**
 * Application Entity.
 */
class Application extends Entity
{
	use TimezonedTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'close_date' => true,
        'created_by' => true,
        'modified_by' => true,
        'bugs' => true,
        'instances' => true,
        'tasks' => true,
        'applications_frameworks' => true,
//         'frameworks' => true,
        'technologies' => true,
    ];
}
