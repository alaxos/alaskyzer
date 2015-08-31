<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Alaxos\Model\Entity\TimezonedTrait;

/**
 * ApplicationsFramework Entity.
 */
class ApplicationsFramework extends Entity
{
	use TimezonedTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'application_id' => true,
        'framework_id' => true,
        'framework_version_id' => true,
        'created_by' => true,
        'modified_by' => true,
        'application' => true,
        'framework' => true,
        'framework_version' => true,
    ];
}
