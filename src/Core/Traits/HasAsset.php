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

use Extly\Joomla\Entity\Core\Asset;
use Extly\Joomla\Entity\Core\Column;

/**
 * Trait for entities that have an asset. Based on asset_id column.
 *
 * @since  1.0.0
 */
trait HasAsset
{
    /**
     * Associated asset.
     *
     * @var  Asset
     */
    protected $asset;

    /**
     * Get the alias for a specific DB column.
     *
     * @param   string  $column  Name of the DB column. Example: created_by
     *
     * @return  string
     */
    abstract public function columnAlias($column);

    /**
     * Get a property of this entity.
     *
     * @param   string  $property  Name of the property to get
     * @param   mixed   $default   Value to use as default if property is not set or is null
     *
     * @return  mixed
     */
    abstract public function get($property, $default = null);

    /**
     * Get the associated asset.
     *
     * @param   bool  $reload  Force asset reloading
     *
     * @return  Asset
     */
    public function asset($reload = false)
    {
        if ($reload || null === $this->asset) {
            $this->asset = $this->loadAsset();
        }

        return $this->asset;
    }

    /**
     * Load the asset from the database.
     *
     * @return  Asset
     */
    protected function loadAsset()
    {
        try {
            $assetId = (int) $this->get($this->columnAlias(Column::ASSET));
        } catch (\Exception $exception) {
            $assetId = 0;
        }

        return $assetId !== 0 ? Asset::find($assetId) : new Asset();
    }
}
