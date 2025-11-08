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

namespace Extly\Joomla\Entity\Categories\Validation;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Categories\Category;
use Extly\Joomla\Entity\Validation\Rule;
use Extly\Joomla\Entity\Validation\Validator;

/**
 * Category validator.
 *
 * @since  1.7.0
 */
class CategoryValidator extends Validator
{
    /**
     * Constructor.
     *
     * @param Article $category Article to validate.
     */
    public function __construct(Category $category)
    {
        parent::__construct($category);

        $this->addRules(
            [
                'access'    => new Rule\IsNullOrPositiveInteger('Valid view level identifier'),
                'extension' => new Rule\IsNotEmptyString('Not empty extension'),
                'level'     => new Rule\IsNullOrPositiveInteger('Valid level'),
                'parent_id' => new Rule\IsNullOrPositiveInteger('Valid parent'),
                'title'     => new Rule\IsNotEmptyString('Not empty title'),
            ]
        );
    }
}
