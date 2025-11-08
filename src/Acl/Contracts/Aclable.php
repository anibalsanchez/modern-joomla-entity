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

namespace Extly\Joomla\Entity\Acl\Contracts;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Contracts\EntityInterface;

/**
 * Describes methods required by entities with ACL support.
 *
 * @since  1.0.0
 */
interface Aclable extends EntityInterface
{
    /**
     * Get the ACL prefix applied to this entity
     *
     * @return  string
     */
    public function aclPrefix();

    /**
     * Get the identifier of the associated asset
     *
     * @return  string
     */
    public function aclAssetName();
}
