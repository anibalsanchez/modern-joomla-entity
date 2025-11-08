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

namespace Extly\Joomla\Entity\Core;

defined('_JEXEC') || die;

/**
 * Columns supported by core.
 *
 * @since   1.0.0
 */
abstract class Column
{
    /**
     * Default column used to store access.
     *
     * @const
     */
    public const ACCESS = 'access';

    /**
     * Default column used to store asset.
     *
     * @const
     */
    public const ASSET = 'asset_id';

    /**
     * Default column used to store client.
     *
     * @const
     */
    public const CLIENT = 'client_id';

    /**
     * Default column used to store featured.
     *
     * @const
     */
    public const FEATURED = 'featured';

    /**
     * Default column used to store images.
     *
     * @const
     */
    public const IMAGES = 'images';

    /**
     * Default column used to store language.
     *
     * @const
     */
    public const LANGUAGE = 'language';

    /**
     * Default column used to store level.
     *
     * @const
     * @since  1.4.0
     */
    public const LEVEL = 'level';

    /**
     * Default column used to store metadata.
     *
     * @const
     */
    public const METADATA = 'metadata';

    /**
     * Default column used to store params.
     *
     * @const
     */
    public const PARAMS = 'params';

    /**
     * Default column used to store parent identifier.
     *
     * @const
     * @since  1.4.0
     */
    public const PARENT = 'parent_id';

    /**
     * Default column used to store publish down date.
     *
     * @const
     */
    public const PUBLISH_DOWN = 'publish_down';

    /**
     * Default column used to store publish up date.
     *
     * @const
     */
    public const PUBLISH_UP = 'publish_up';

    /**
     * Default column used to store state.
     *
     * @const
     */
    public const STATE = 'published';
}
