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

use Extly\Joomla\Entity\Searcher\BaseSearcher;
use Joomla\CMS\Factory;

/**
 * Database finder.
 *
 * @since  1.4.0
 */
abstract class DatabaseSearcher extends BaseSearcher
{
    /**
     * Database driver.
     *
     * @var  \JDatabaseDriver
     */
    protected $db;

    /**
     * Constructor
     *
     * @param   array  $options  Find options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->db = $this->options->get('db', Factory::getDbo());
    }

    /**
     * Default options for this finder.
     *
     * @return  array
     */
    public function defaultOptions()
    {
        return [
            'list.start' => 0,
            'list.limit' => 20,
        ];
    }

    /**
     * Retrieve the search query.
     *
     * @return  \JDatabaseQuery
     */
    abstract public function searchQuery();

    /**
     * Execute the search.
     *
     * @return  array
     */
    public function search()
    {
        $this->db->setQuery(
            $this->searchQuery(),
            (int) $this->options->get('list.start'),
            (int) $this->options->get('list.limit', 20)
        );

        return $this->db->loadAssocList() ?: [];
    }
}
