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

use Extly\Joomla\Entity\Core\Column;

/**
 * Trait for entities with images.
 *
 * @since   1.0.0
 */
trait HasImages
{
    /**
     * Images.
     *
     * @var  array
     */
    protected $images;

    /**
     * Key of the intro image.
     *
     * @var  string
     */
    protected static $introImageKey = 'intro';

    /**
     * Key of the full view image.
     *
     * @var  string
     */
    protected static $fullImageKey = 'full';

    /**
     * Get the alias for a specific DB column.
     *
     * @param   string  $column  Name of the DB column. Example: created_by
     *
     * @return  string
     */
    abstract public function columnAlias($column);

    /**
     * Get the content of a column with data stored in JSON.
     *
     * @param   string  $property  Name of the column storing data
     *
     * @return  array
     */
    abstract public function json($property);

    /**
     * Get the full text image.
     *
     * @return  array
     */
    public function getFullTextImage()
    {
        $images = $this->getImages();

        return array_key_exists(self::$fullImageKey, $images) ? $images[self::$fullImageKey] : [];
    }

    /**
     * Get article images.
     *
     * @param   bool  $reload  Force data reloading
     *
     * @return  array
     */
    public function getImages($reload = false)
    {
        if ($reload || null === $this->images) {
            $this->images = $this->loadImages();
        }

        return $this->images;
    }

    /**
     * Get the article intro image.
     *
     * @return  array
     */
    public function getIntroImage()
    {
        $images = $this->getImages();

        return array_key_exists(self::$introImageKey, $images) ? $images[self::$introImageKey] : [];
    }

    /**
     * Has this article an full text image?
     *
     * @return  bool
     */
    public function hasFullTextImage()
    {
        return array_key_exists(self::$fullImageKey, $this->getImages());
    }

    /**
     * Has this article an intro image?
     *
     * @return  bool
     */
    public function hasIntroImage()
    {
        return array_key_exists(self::$introImageKey, $this->getImages());
    }

    /**
     * Load images information.
     *
     * @return  array
     */
    protected function loadImages()
    {
        if (!$this->isLoaded()) {
            return [];
        }

        $data = (array) $this->json($this->columnAlias(Column::IMAGES));

        $images = [];

        if ($introImage = $this->parseImage('intro', $data)) {
            $images[self::$introImageKey] = $introImage;
        }

        if ($fullImage = $this->parseImage('fulltext', $data)) {
            $images[self::$fullImageKey] = $fullImage;
        }

        return $images;
    }

    /**
     * Parse an image information from db data.
     *
     * @param   string  $name  Name of the image: intro | fulltext
     * @param   array   $data  Data from the database
     *
     * @return  array
     */
    private function parseImage($name, array $data)
    {
        $image = [];

        if (empty($data['image_'.$name])) {
            return $image;
        }

        $properties = [
            'url'     => 'image_'.$name,
            'float'   => 'float_'.$name,
            'alt'     => 'image_'.$name.'_alt',
            'caption' => 'image_'.$name.'_caption',
        ];

        foreach ($properties as $key => $property) {
            if (isset($data[$property]) && $data[$property] != '') {
                $image[$key] = $data[$property];
            }
        }

        return $image;
    }
}
