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

namespace Extly\Joomla\Entity\Tests\Translation\Stubs;

use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Translation\Contracts\Translatable;

/**
 * Entity to test Acl decorator.
 *
 * @since  1.1.0
 *
 * @codeCoverageIgnore
 */
class TranslatableEntity extends ComponentEntity implements Translatable
{
    /**
     * Available translations.
     *
     * @var  array
     */
    public $translations = [];

    /**
     * Get a translation.
     *
     * @param   string  $langTag  Language string. Example: es-ES
     *
     * @return  static
     *
     * @throws  \InvalidArgumentException
     */
    public function translation($langTag)
    {
        if (!isset($this->translations[$langTag])) {
            $msg = sprintf('Article %d does not have %s language', $this->id(), $langTag);

            throw new \InvalidArgumentException($msg);
        }

        return $this->translations[$langTag];
    }
}
