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

namespace Extly\Joomla\Entity\Command\Database;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Command\BaseCommand;
use Extly\Joomla\Entity\Command\Contracts\CommandInterface;
use Joomla\CMS\Factory;

/**
 * Empty a database table.
 *
 * @since  1.8
 */
final class EmptyTable extends BaseCommand implements CommandInterface
{
    /**
     * Database driver.
     *
     * @var  \JDatabaseDriver
     */
    private $jDatabaseDriver;

    /**
     * Name of the table to empty.
     *
     * @var  string
     */
    private $tableName;

    /**
     * Constructor.
     *
     * @param   string  $name     Name of the table to empty.
     * @param   array   $options  Additional settings
     */
    public function __construct(string $name, array $options = [])
    {
        $this->tableName = $name;
        $this->jDatabaseDriver = $options['db'] ?? Factory::getDbo();

        unset($options['db']);

        parent::__construct($options);
    }

    /**
     * Execute the command.
     *
     * @return  mixed
     */
    public function execute()
    {
        try {
            $query = $this->jDatabaseDriver->getQuery(true)
                ->delete($this->jDatabaseDriver->qn($this->tableName));

            $this->jDatabaseDriver->setQuery($query);
            $this->jDatabaseDriver->execute();
        } catch (\RuntimeException $runtimeException) {
            throw new \RuntimeException(sprintf('Error emptying DB table `%s`: %s', $this->tableName, $runtimeException->getMessage()), $runtimeException->getCode(), $runtimeException);
        }
    }
}
