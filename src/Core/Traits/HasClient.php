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

namespace Extly\Joomla\Entity\Core\Traits;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Core\Client\Administrator;
use Extly\Joomla\Entity\Core\Client\Client;
use Extly\Joomla\Entity\Core\Client\ClientInterface;
use Extly\Joomla\Entity\Core\Client\Site;
use Extly\Joomla\Entity\Core\Column;

/**
 * Trait for entities that have an associated client.
 *
 * @since  1.0.0
 */
trait HasClient
{
    /**
     * Associated client.
     *
     * @var  ClientInterface
     */
    protected $client;

    /**
     * Switch to admin client.
     *
     * @return  self
     */
    public function admin()
    {
        $this->client = new Administrator();

        return $this;
    }

    /**
     * Get the associated client.
     *
     * @param   bool  $reload  Force reloading
     *
     * @return  ClientInterface
     */
    public function client($reload = false)
    {
        if ($reload || null === $this->client) {
            $this->client = $this->loadClient();
        }

        return $this->client;
    }

    /**
     * Switch to site client.
     *
     * @return  self
     */
    public function site()
    {
        $this->client = new Site();

        return $this;
    }

    /**
     * Load the client from the database.
     *
     * @return  Category
     */
    protected function loadClient()
    {
        $clientId = (int) $this->get($this->columnAlias(Column::CLIENT));

        return $clientId !== 0 ? Client::admin() : Client::site();
    }
}
