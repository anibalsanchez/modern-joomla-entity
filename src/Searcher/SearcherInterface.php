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

namespace Extly\Joomla\Entity\Searcher;

defined('_JEXEC') || die;

/**
 * Searcher interface.
 *
 * @since  1.4.0
 */
interface SearcherInterface
{
    /**
     * Execute the search.
     *
     * @return  array
     */
    public function search();
}
