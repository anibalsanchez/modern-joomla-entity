<?php

/*
 * @package     Modern Joomla Entity
 *
 * @author      Anibal Sanchez <team@extly.com>
 * @copyright   Copyright (c)2025 Anibal Sanchez. All rights reserved.
 *              Based on phproberto/joomla-entity by Roberto Segura López
 *
 * @license     LGPL-2.1+
 *
 * @see         https://www.extly.com
 */

namespace Extly\Joomla\Entity\Tests\Acl\Stubs;

use Extly\Joomla\Entity\Acl\Contracts\Aclable;
use Extly\Joomla\Entity\Acl\Traits\HasAcl;
use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Core\Contracts\Publishable;
use Extly\Joomla\Entity\Core\Traits\HasState;

/**
 * Entity to test Acl decorator.
 *
 * @since  1.1.0
 */
class PublishableEntityWithAcl extends ComponentEntity implements Aclable, Publishable
{
    use HasAcl;
    use HasState;
}
