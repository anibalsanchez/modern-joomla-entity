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

namespace Extly\Joomla\Entity\Tags;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Acl\Contracts\Aclable;
use Extly\Joomla\Entity\Acl\Traits\HasAcl;
use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Core\Contracts\Publishable;
use Extly\Joomla\Entity\Core\Traits as CoreTraits;

/**
 * Tag entity.
 *
 * @since   1.0.0
 */
class Tag extends ComponentEntity implements Aclable, Publishable
{
    use HasAcl;
    use CoreTraits\HasImages;
    use CoreTraits\HasLink;
    use CoreTraits\HasMetadata;
    use CoreTraits\HasParams;
    use CoreTraits\HasState;

    /**
     * Get a table.
     *
     * @param   string  $name     The table name. Optional.
     * @param   string  $prefix   The class prefix. Optional.
     * @param   array   $options  Configuration array for model. Optional.
     *
     * @return  \JTable
     *
     * @codeCoverageIgnore
     */
    public function table($name = '', $prefix = null, $options = [])
    {
        \Joomla\CMS\Table\Table::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_tags/tables');

        $name = $name ?: 'Tag';
        $prefix = $prefix ?: 'TagsTable';

        return parent::table($name, $prefix, $options);
    }

    /**
     * Load the link to this entity.
     *
     * @return  atring
     *
     * @codeCoverageIgnore
     */
    protected function loadLink()
    {
        if (!$this->hasId()) {
            return null;
        }

        \JLoader::register('TagsHelperRoute', JPATH_BASE.'/components/com_tags/helpers/route.php');

        return \Joomla\CMS\Router\Route::_(\TagsHelperRoute::getTagRoute($this->id().'-'.$this->get('alias')));
    }
}
