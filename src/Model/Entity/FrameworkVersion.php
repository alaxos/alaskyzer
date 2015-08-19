<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Alaxos\Model\Entity\TimezonedTrait;

/**
 * FrameworkVersion Entity.
 */
class FrameworkVersion extends Entity
{
	use TimezonedTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'framework_id' => true,
        'name' => true,
        'created_by' => true,
        'modified_by' => true,
        'framework' => true,
    ];
}
