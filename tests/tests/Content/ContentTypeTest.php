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

namespace Extly\Joomla\Entity\Tests\Content;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Content\ContentType;
use Joomla\Registry\Registry;

/**
 * ContentType tests.
 *
 * @since   1.6.0
 */
class ContentTypeTest extends \TestCaseDatabase
{
    /**
     * Preloaded entity for tests.
     *
     * @var  ContentType
     */
    private $contentType;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return  void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->contentType = ContentType::find(1);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aliasReturnsExpectedValue()
    {
        $this->assertSame('com_content.article', $this->contentType->alias());
    }

    /**
     * @test
     *
     * @return void
     */
    public function contentHistoryOptionsReturnsCorrectValue()
    {
        $contentType = new ContentType();
        $contentType->bind(
            [
                $contentType->primaryKey() => '99',
                'content_history_options' => '{"formFile":"administrator\/components\/com_content\/models\/forms\/article.xml", "hideFields":["asset_id","checked_out","checked_out_time","version"],"ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time", "version", "hits"],"convertToInt":["publish_up", "publish_down", "featured", "ordering"],"displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"} ]}',
            ]
        );

        $this->assertSame(
            'administrator/components/com_content/models/forms/article.xml',
            $contentType->contentHistoryOptions()->formFile
        );

        $contentType->assign('content_history_options', '');

        $this->assertSame(null, $contentType->contentHistoryOptions());
    }

    /**
     * @test
     *
     * @return void
     */
    public function fieldMappingsReturnsCorrectValue()
    {
        $contentType = new ContentType();
        $contentType->bind(
            [
                $contentType->primaryKey() => '99',
                'field_mappings' => '{"common":{"core_content_item_id":"id","core_title":"name","core_state":"published","core_alias":"alias","core_created_time":"created","core_modified_time":"modified","core_body":"description", "core_hits":"null","core_publish_up":"publish_up","core_publish_down":"publish_down","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"images", "core_urls":"link", "core_version":"version", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"catid", "core_xreference":"null", "asset_id":"null"}, "special":{"imptotal":"imptotal", "impmade":"impmade", "clicks":"clicks", "clickurl":"clickurl", "custombannercode":"custombannercode", "cid":"cid", "purchase_type":"purchase_type", "track_impressions":"track_impressions", "track_clicks":"track_clicks"}}',
            ]
        );

        $fieldMappings = $contentType->fieldMappings();

        $this->assertSame('id', $fieldMappings->common->core_content_item_id);

        $contentType->assign('field_mappings', '');

        $this->assertSame(null, $contentType->fieldMappings());
    }

    /**
     * @test
     *
     * @return void
     */
    public function fromAliasReturnsExpectedContentType()
    {
        $contentType = ContentType::fromAlias('com_users.user');

        $this->assertInstanceOf(ContentType::class, $contentType);
        $this->assertSame('User', $contentType->title());
    }

    /**
     * @test
     *
     * @return  void
     */
    public function loadWorks()
    {
        $this->assertSame('com_content.article', $this->contentType->get('type_alias'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function tableSettingsReturnsCorrectValue()
    {
        $contentType = new ContentType();
        $contentType->bind(
            [
                $contentType->primaryKey() => '99',
                'table' => '{"special":{"dbtable":"#__banner_clients","key":"id","type":"Client","prefix":"BannersTable"}}',
            ]
        );

        $tableSettings = $contentType->tableSettings();

        $this->assertSame('#__banner_clients', $tableSettings->special->dbtable);

        $contentType->assign('table', '');

        $this->assertSame(null, $contentType->tableSettings());
    }

    /**
     * @test
     *
     * @return void
     */
    public function titleReturnsExpectedTitle()
    {
        $this->assertSame('Article', $this->contentType->title());
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_content_types', JPATH_TEST_DATABASE.'/jos_content_types.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
