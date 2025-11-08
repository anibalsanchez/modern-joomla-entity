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

/**
 * Trait for entities with associations.
 *
 * @since   1.0.0
 */
trait HasAssociations
{
    /**
     * Language associations.
     *
     * @var  array
     */
    protected $associations;

    /**
     * Get an association by its language tag.
     *
     * @param   string  $langTag  Language tag
     *
     * @return  \stdClass
     */
    public function association($langTag)
    {
        $associations = $this->associations();

        if (!array_key_exists($langTag, $associations)) {
            $msg = sprintf('Entity %d does not have %s association', $this->id(), $langTag);

            throw new \InvalidArgumentException($msg);
        }

        return $associations[$langTag];
    }

    /**
     * Get entity's language associations.
     *
     * @param   bool  $reload  Force data reloading
     *
     * @return  \stdClass[]
     */
    public function associations($reload = false)
    {
        if ($reload || null === $this->associations) {
            $this->associations = $this->loadAssociations();
        }

        return $this->associations;
    }

    /**
     * Get the ids of the entity's language associations.
     *
     * @return  array
     */
    public function associationsIds()
    {
        return array_filter(
            array_map(
                fn ($association) => (int) $association->id,
                $this->associations()
            )
        );
    }

    /**
     * Check if this entity has a specific association.
     *
     * @param   string   $langTag  Language tag
     *
     * @return  bool
     */
    public function hasAssociation($langTag)
    {
        return array_key_exists($langTag, $this->associations());
    }

    /**
     * Check if this entity has an association by its id.
     *
     * @param   int  $id  Association identifier
     *
     * @return  bool
     */
    public function hasAssociationById($id)
    {
        return in_array((int) $id, $this->associationsIds(), true);
    }

    /**
     * Check if this entity has associations.
     *
     * @return  bool
     */
    public function hasAssociations()
    {
        return !empty($this->associations());
    }

    /**
     * Load associations from DB.
     *
     * @return  \stdClass[]
     */
    abstract protected function loadAssociations();
}
