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

namespace Extly\Joomla\Entity\Content\Validation;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Content\Article;
use Extly\Joomla\Entity\Validation\Rule;
use Extly\Joomla\Entity\Validation\Validator;

/**
 * ArticleValidator validator.
 *
 * @since  1.0.0
 */
class ArticleValidator extends Validator
{
    /**
     * Constructor.
     *
     * @param   Article  $article  Article to validate.
     */
    public function __construct(Article $article)
    {
        parent::__construct($article);

        $this->addRules(
            [
                'title'  => new Rule\IsNotEmptyString('Not empty title'),
                'catid'  => new Rule\IsPositiveInteger('Valid category identifier'),
                'access' => new Rule\IsNullOrPositiveInteger('Valid view level identifier'),
            ]
        );
    }
}
