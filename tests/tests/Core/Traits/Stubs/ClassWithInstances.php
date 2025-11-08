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

namespace Extly\Joomla\Entity\Tests\Core\Traits\Stubs;

use Extly\Joomla\Entity\Core\Traits\HasInstances;

/**
 * Sample class to test HasInstances trait.
 *
 * @since  1.1.0
 */
class ClassWithInstances
{
    use HasInstances;

    /**
     * Class identifier
     *
     * @var  int
     */
    protected $id;

    /**
     * Name property.
     *
     * @var  string
     */
    protected $name;

    /**
     * Constructor.
     *
     * @param   int  $id  Identifier
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the Class identifier.
     *
     * @return  int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the Name property.
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the Name property.
     *
     * @param   string  $name  the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
