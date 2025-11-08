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
 * Executes a SQL file.
 *
 * @since  1.8
 */
final class ExecuteSQLFile extends BaseCommand implements CommandInterface
{
    /**
     * Database driver.
     *
     * @var  \JDatabaseDriver
     */
    private $jDatabaseDriver;

    /**
     * Path to the file to execute.
     *
     * @var  string
     */
    private $file;

    /**
     * Constructor.
     *
     * @param   string  $filePath  File to execute.
     * @param   array   $options   Additional settings
     */
    public function __construct(string $filePath, array $options = [])
    {
        $this->file = $filePath;
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
        $sql = file_get_contents($this->file);

        foreach ($this->jDatabaseDriver->splitSql($sql) as $query) {
            try {
                $this->jDatabaseDriver->setQuery($query)->execute();
            } catch (\Exception $e) {
                // If the query fails we will go on. It just means the index to be dropped does not exist.
            }
        }
    }
}
