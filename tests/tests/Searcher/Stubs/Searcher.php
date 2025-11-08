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

namespace Extly\Joomla\Entity\Tests\Searcher\Stubs;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Searcher\BaseSearcher;

/**
 * Category searcher tests.
 *
 * @since   1.4.0
 */
class Searcher extends BaseSearcher
{
    /**
     * Default options to initialise searcher.
     *
     * @return  array
     */
    public function defaultOptions()
    {
        return array_merge(
            parent::defaultOptions(),
            [
                'option'         => 'default-value',
                'another-option' => 'another-default-value',
            ]
        );
    }
}
