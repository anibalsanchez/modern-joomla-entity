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

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Content\Article;
use Extly\Joomla\Entity\Content\Category;
use Extly\Joomla\Entity\Content\Validation\ArticleValidator;
use Extly\Joomla\Entity\Core\Column as CoreColumn;
use Extly\Joomla\Entity\Tags\Tag;
use Extly\Joomla\Entity\Users\User;
use Joomla\Registry\Registry;

/**
 * Article entity tests.
 *
 * @since   1.1.0
 */
class ArticleTest extends \TestCaseDatabase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return  void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();

        \Joomla\CMS\Factory::$session = $this->getMockSession();
        \Joomla\CMS\Factory::$config = $this->getMockConfig();
        \Joomla\CMS\Factory::$application = $this->getMockCmsApp();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        Article::clearAll();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     *
     * @expectedException  \Extly\Joomla\Entity\Exception\SaveException
     */
    public function cannotSaveIfValdationFails()
    {
        $article = new Article();
        $article->bind(
            [
                'access' => 1,
                'title' => 'Sample article',
            ]
        );
        $article->save();
    }

    /**
     * @test
     *
     * @return void
     */
    public function contentTypeAliasReturnsExpectedValue()
    {
        $this->assertSame('com_content.article', Article::contentTypeAlias());
    }

    /**
     * @test
     *
     * @return void
     */
    public function saveWorksIfValidationPasses()
    {
        $article = new Article();
        $article->bind(
            [
                'title'  => 'My article',
                'catid'  => 13,
            ]
        );

        $article->save();

        $this->assertTrue($article->hasId());
    }

    /**
     * access retrieved.
     *
     * @return  void
     */
    public function testAccessRetrieved()
    {
        $article = $this->getMockBuilder(Article::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $article->method('columnAlias')
            ->willReturn('access');

        $reflectionClass = new \ReflectionClass($article);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($article, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($article, ['id' => 999, 'access' => 0]);

        $this->assertSame(0, $article->access());

        $rowProperty->setValue($article, ['id' => 999, 'access' => 1]);

        $this->assertSame(1, $article->access());
    }

    /**
     * Article loaded.
     *
     * @return  void
     */
    public function testArticleLoaded()
    {
        $article = Article::load(1);

        $this->assertTrue($article->isLoaded());
    }

    /**
     * Asset can be retrieved.
     *
     * @return  void
     */
    public function testAssetCanBeRetrieved()
    {
        $article = Article::find(1);

        $asset = $article->asset();

        $this->assertInstanceOf(\Extly\Joomla\Entity\Core\Asset::class, $asset);
        $this->assertNotSame(0, $asset->id());
    }

    /**
     * author retrieved.
     *
     * @return  void
     */
    public function testAuthorRetrieved()
    {
        $article = new Article(999);

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($article, ['id' => 999, 'created_by' => 666]);

        $this->assertSame(User::find(666), $article->author());
    }

    /**
     * editor retrieved.
     *
     * @return  void
     */
    public function testEditorRetrieved()
    {
        $article = new Article(999);

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($article, ['id' => 999, 'modified_by' => 666]);

        $this->assertSame(User::find(666), $article->editor());
    }

    /**
     * Category can be retrieved.
     *
     * @return  void
     */
    public function testCategoryCanBeRetrieved()
    {
        $article = new Article(999);

        $reflectionClass = new \ReflectionClass($article);

        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($article, ['id' => 999]);

        $this->assertEquals(new Category(), $article->category());

        $reflectionProperty->setValue($article, ['id' => 999, 'catid' => 666]);

        // No reload = same category
        $this->assertEquals(new Category(), $article->category());
        $this->assertEquals(Category::find(666), $article->category(true));
    }

    /**
     * getImages returns correct value.
     *
     * @return  void
     */
    public function testGetImagesReturnsCorrectValue()
    {
        $_SERVER['HTTP_HOST'] = 'joomla-entity.test.com';
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $article = new Article(999);

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($article, ['id' => 999, 'images' => '']);

        $this->assertSame([], $article->getImages(true));

        $reflectionProperty->setValue(
            $article,
            [
                'id' => 999,
                'images' => '{"image_intro":"images\/joomla_black.png","float_intro":"left","image_intro_alt":"Alt text","image_intro_caption":"Caption text","image_fulltext":"images\/fulltext.png","float_fulltext":"right","image_fulltext_alt":"Alt fulltext","image_fulltext_caption":"Caption fulltext"}',
            ]
        );

        $this->assertSame([], $article->getImages());

        $expected = [
            'intro' => [
                'url'     => 'images/joomla_black.png',
                'float'   => 'left',
                'alt'     => 'Alt text',
                'caption' => 'Caption text',
            ],
            'full' => [
                'url'     => 'images/fulltext.png',
                'float'   => 'right',
                'alt'     => 'Alt fulltext',
                'caption' => 'Caption fulltext',
            ],
        ];
        $images = $article->getImages(true);

        $this->assertEquals($expected, $article->getImages(true));
    }

    /**
     * getMetadata returns data.
     *
     * @return  void
     */
    public function testGetMetadataReturnsData()
    {
        $article = new Article(999);

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($article, ['id' => 999, 'metadata' => '{"foo":"bar"}']);

        $expected = [
            'foo' => 'bar',
        ];

        $this->assertEquals($expected, $article->metadata());
    }

    /**
     * getArticlesModel returns correct value.
     *
     * @return  void
     */
    public function testGetArticlesModelReturnsCorrectValue()
    {
        $article = new Article();

        $reflectionClass = new \ReflectionClass($article);
        $reflectionMethod = $reflectionClass->getMethod('getArticlesModel');
        $reflectionMethod->setAccessible(true);

        $model = $reflectionMethod->invoke($article);

        $this->assertInstanceOf('ContentModelArticles', $model);
        $this->assertSame(null, $model->getState('filter.article_id'));
        $this->assertEquals(new Registry(), $model->getState('params'));

        $article = new Article();
        $registry = new Registry(['foo' => 'var']);

        $model = $reflectionMethod->invoke($article, [
            'filter.article_id' => [34],
            'params'            => $registry,
        ]
        );

        $this->assertInstanceOf('ContentModelArticles', $model);
        $this->assertSame([34], $model->getState('filter.article_id'));
        $this->assertEquals($registry, $model->getState('params'));
    }

    /**
     * getUrls returns correct information.
     *
     * @return  void
     */
    public function testGetUrlsReturnsCorrectInformation()
    {
        $article = new Article(999);

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($article, ['id' => 999, 'urls' => '']);

        $this->assertEquals([], $article->getUrls());

        $article = Article::fresh(999);
        $reflectionProperty->setValue($article, ['id' => 999, 'urls' => '{}']);

        $this->assertEquals([], $article->getUrls());

        $article = Article::fresh(999);
        $reflectionProperty->setValue(
            $article,
            [
                'id' => 999,
                'urls' => '{"urla":"","urlatext":"","targeta":"","urlb":"","urlbtext":"","targetb":"","urlc":"","urlctext":"","targetc":""}',
            ]
        );

        $this->assertEquals([], $article->getUrls());

        $article = Article::fresh(999);
        $reflectionProperty->setValue(
            $article,
            [
                'id' => 999,
                'urls' => '{"urla":"http://google.com","urlatext":"Google","targeta":"0"}',
            ]
        );

        $expected = [
            'a' => [
                'url'    => 'http://google.com',
                'text'   => 'Google',
                'target' => '0',
            ],
        ];

        $this->assertEquals($expected, $article->getUrls());

        $article = Article::fresh(999);
        $reflectionProperty->setValue(
            $article,
            [
                'id' => 999,
                'urls' => '{"urla":"http:\/\/google.es","urlatext":"Google","targeta":"1","urlb":"http:\/\/yahoo.com","urlbtext":"Yahoo","targetb":"0","urlc":"http://www.phproberto.com","urlctext":"Phproberto","targetc":""}',
            ]
        );

        $expected = [
            'a' => [
                'url'    => 'http://google.es',
                'text'   => 'Google',
                'target' => '1',
            ],
            'b' => [
                'url'    => 'http://yahoo.com',
                'text'   => 'Yahoo',
                'target' => '0',
            ],
            'c' => [
                'url'    => 'http://www.phproberto.com',
                'text'   => 'Phproberto',
            ],
        ];

        $this->assertEquals($expected, $article->getUrls());
    }

    /**
     * hasFullTextImage returns correct value.
     *
     * @return  void
     */
    public function testhasFullTextImageReturnsCorrectValue()
    {
        $article = new Article(999);

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($article, ['id' => 999, 'images' => '']);

        $this->assertFalse($article->hasFullTextImage());

        $article = Article::fresh(999);
        $reflectionProperty->setValue(
            $article,
            [
                'id' => 999,
                'images' => '{"image_fulltext":"images\/joomla_black.png"}',
            ]
        );

        $this->assertTrue($article->hasFullTextImage());

        $article = Article::fresh(999);
        $reflectionProperty->setValue($article, ['id' => 999, 'images' => '']);

        $this->assertFalse($article->hasFullTextImage());

        $article = Article::fresh(999);
        $reflectionProperty->setValue(
            $article,
            [
                'id' => 999,
                'images' => '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"","float_fulltext":"","image_fulltext_alt":"","image_fulltext_caption":""}',
            ]
        );

        $this->assertFalse($article->hasFullTextImage());
    }

    /**
     * hasIntroImage returns correct value.
     *
     * @return  void
     */
    public function testhasIntroImageReturnsCorrectValue()
    {
        $article = new Article(999);

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($article, ['id' => 999, 'images' => '']);

        $this->assertFalse($article->hasIntroImage());

        $article = Article::fresh(999);
        $reflectionProperty->setValue(
            $article,
            [
                'id' => 999,
                'images' => '{"image_intro":"images\/joomla_black.png"}',
            ]
        );

        $this->assertTrue($article->hasIntroImage());

        $article = Article::fresh(999);
        $reflectionProperty->setValue($article, ['id' => 999, 'images' => '']);

        $this->assertFalse($article->hasIntroImage());

        $article = Article::fresh(999);
        $reflectionProperty->setValue(
            $article,
            [
                'id' => 999,
                'images' => '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"","float_fulltext":"","image_fulltext_alt":"","image_fulltext_caption":""}',
            ]
        );

        $this->assertFalse($article->hasIntroImage());
    }

    /**
     * isFeatured returns correct value.
     *
     * @return  void
     */
    public function testIsFeaturedReturnsCorrectValue()
    {
        $article = new Article(999);

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($article, ['id' => 999, CoreColumn::FEATURED => 0]);

        $this->assertFalse($article->isFeatured());

        $reflectionProperty->setValue($article, ['id' => 999, CoreColumn::FEATURED => '0']);

        $this->assertFalse($article->isFeatured());

        $reflectionProperty->setValue($article, ['id' => 999, CoreColumn::FEATURED => '1']);

        $this->assertTrue($article->isFeatured());

        $reflectionProperty->setValue($article, ['id' => 999, CoreColumn::FEATURED => 1]);

        $this->assertTrue($article->isFeatured());
    }

    /**
     * isPublished returns correct value.
     *
     * @return  void
     */
    public function testIsPublishedReturnsFalseIfHasUnpublishedState()
    {
        $entity = $this->getMockBuilder(Article::class)
            ->setMethods(['isOnState'])
            ->getMock();

        $entity->expects($this->once())
            ->method('isOnState')
            ->willReturn(false);

        $this->assertFalse($entity->isPublished());
    }

    /**
     * isPublished returns false when article is not published up.
     *
     * @return  void
     */
    public function testIsPublishedReturnsFalseWhenArticleIsNotPublishedUp()
    {
        $entity = $this->getMockBuilder(Article::class)
            ->setMethods(['isOnState', 'isPublishedUp'])
            ->getMock();

        $entity->expects($this->once())
            ->method('isOnState')
            ->with($this->equalTo(Article::STATE_PUBLISHED))
            ->willReturn(true);

        $entity->expects($this->once())
            ->method('isPublishedUp')
            ->willReturn(false);

        $this->assertFalse($entity->isPublished());
    }

    /**
     * isPublished returns false when article is published down.
     *
     * @return  void
     */
    public function testIsPublishedReturnsFalseWhenArticleIsPublishedDow()
    {
        $entity = $this->getMockBuilder(Article::class)
            ->setMethods(['isOnState', 'isPublishedUp', 'isPublishedDown'])
            ->getMock();

        $entity->expects($this->once())
            ->method('isOnState')
            ->with($this->equalTo(Article::STATE_PUBLISHED))
            ->willReturn(true);

        $entity->expects($this->once())
            ->method('isPublishedUp')
            ->willReturn(true);

        $entity->expects($this->once())
            ->method('isPublishedDown')
            ->willReturn(true);

        $this->assertFalse($entity->isPublished());
    }

    /**
     * isPublished returns false when category is unpublished.
     *
     * @return  void
     */
    public function testIsPublishedReturnsFalseWhenCategoryIsUnpublished()
    {
        $categoryMock = $this->getMockBuilder('MockedCategory')
            ->setMethods(['isPublished'])
            ->getMock();

        $categoryMock->expects($this->once())
            ->method('isPublished')
            ->willReturn(false);

        $entity = $this->getMockBuilder(Article::class)
            ->setMethods(['category', 'isOnState', 'isPublishedUp', 'isPublishedDown'])
            ->getMock();

        $entity->expects($this->once())
            ->method('isOnState')
            ->with($this->equalTo(Article::STATE_PUBLISHED))
            ->willReturn(true);

        $entity->expects($this->once())
            ->method('isPublishedUp')
            ->willReturn(true);

        $entity->expects($this->once())
            ->method('isPublishedDown')
            ->willReturn(false);

        $entity->expects($this->once())
            ->method('category')
            ->willReturn($categoryMock);

        $this->assertFalse($entity->isPublished());
    }

    /**
     * isPublished returns true when everything is ok.
     *
     * @return  void
     */
    public function testIsPublishedReturnsTrueWhenEverythingIsOk()
    {
        $categoryMock = $this->getMockBuilder('MockedCategory')
            ->setMethods(['isPublished'])
            ->getMock();

        $categoryMock->expects($this->once())
            ->method('isPublished')
            ->willReturn(true);

        $entity = $this->getMockBuilder(Article::class)
            ->setMethods(['category', 'isOnState', 'isPublishedUp', 'isPublishedDown'])
            ->getMock();

        $entity->expects($this->once())
            ->method('isOnState')
            ->with($this->equalTo(Article::STATE_PUBLISHED))
            ->willReturn(true);

        $entity->expects($this->once())
            ->method('isPublishedUp')
            ->willReturn(true);

        $entity->expects($this->once())
            ->method('isPublishedDown')
            ->willReturn(false);

        $entity->expects($this->once())
            ->method('category')
            ->willReturn($categoryMock);

        $this->assertTrue($entity->isPublished());
    }

    /**
     * loadTags returns empty collection for missing id.
     *
     * @return  void
     */
    public function testLoadTagsReturnsEmptyCollectionForMissingId()
    {
        $article = new Article();

        $reflectionClass = new \ReflectionClass($article);
        $reflectionMethod = $reflectionClass->getMethod('loadTags');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(new Collection(), $reflectionMethod->invoke($article));
    }

    /**
     * loadTags loads correct data for existing id.
     *
     * @return  void
     */
    public function testLoadTagsReturnsCorrectDataForExistingId()
    {
        $helperMock = $this->getMockBuilder(\Joomla\CMS\Helper\TagsHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItemTags'])
            ->getMock();

        $helperMock->method('getItemTags')
            ->willReturn(
                [
                    (object) [
                        'id' => 23,
                        'title' => 'Sample tag',
                    ],
                ]
            );

        $entity = $this->getMockBuilder(Article::class)
            ->setMethods(['getTagsHelperInstance'])
            ->getMock();

        $entity
            ->method('getTagsHelperInstance')
            ->willReturn($helperMock);

        $reflection = new \ReflectionClass($entity);
        $reflectionProperty = $reflection->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entity, 999);

        $reflectionMethod = $reflection->getMethod('loadTags');
        $reflectionMethod->setAccessible(true);

        $tag = new Tag(23);

        $tagReflection = new \ReflectionClass($tag);
        $rowProperty = $reflection->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($tag, ['id' => 23, 'title' => 'Sample tag']);

        $this->assertEquals(new Collection([$tag]), $reflectionMethod->invoke($entity));
    }

    /**
     * loadTranslations returns empty collection for missing id.
     *
     * @return  void
     */
    public function testLoadTranslationsReturnsEmptyCollectionForMissingId()
    {
        $article = new Article();

        $reflectionClass = new \ReflectionClass($article);
        $reflectionMethod = $reflectionClass->getMethod('loadTranslations');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(new Collection(), $reflectionMethod->invoke($article));
    }

    /**
     * loadTranslations returns empty collection for missing translations.
     *
     * @return  void
     */
    public function testLoadTranslationsReturnsEmptyCollectionForMissingTranslations()
    {
        $article = $this->getMockBuilder(Article::class)
            ->setMethods(['associations'])
            ->getMock();

        $article
            ->expects($this->once())
            ->method('associations')
            ->willReturn([]);

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($article, 333);

        $reflectionMethod = $reflectionClass->getMethod('loadTranslations');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(new Collection(), $reflectionMethod->invoke($article));
    }

    /**
     * loadTranslations loads correct data for existing id.
     *
     * @return  void
     */
    public function testLoadTranslationsReturnsCorrectDataForExistingId()
    {
        $articles = [
            333 => ['id' => 333, 'title' => 'Source article', 'language' => 'en-GB'],
            666 => ['id' => 666, 'title' => 'Spanish translation', 'language' => 'es-ES'],
            999 => ['id' => 999, 'title' => 'Brasialian translation', 'language' => 'pt-BR'],

        ];

        $associations = [
            'es-ES' => new Article(666),
            'pt-BR' => new Article(999),
        ];

        $article = $this->getMockBuilder(Article::class)
            ->setMethods(['associations', 'getArticlesModel'])
            ->getMock();

        $article
            ->expects($this->once())
            ->method('associations')
            ->willReturn($associations);

        $getItemsResponse = [
            (object) $articles[666],
            (object) $articles[999],
        ];

        $article
            ->expects($this->once())
            ->method('getArticlesModel')
            ->willReturn($this->getArticlesModelMock($getItemsResponse));

        $reflectionClass = new \ReflectionClass($article);

        $reflectionMethod = $reflectionClass->getMethod('loadTranslations');
        $reflectionMethod->setAccessible(true);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($article, 333);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($article, $articles[333]);

        $spanish = new Article(666);
        $rowProperty->setValue($spanish, $articles[666]);

        $brasilian = new Article(999);
        $rowProperty->setValue($brasilian, $articles[999]);

        $expectedCollection = new Collection([$spanish, $brasilian]);

        // We can only compare ids because mocked article returns a collection of mocked articles
        $this->assertEquals($expectedCollection->ids(), $reflectionMethod->invoke($article)->ids());
    }

    /**
     * params returns parameters.
     *
     * @return  void
     */
    public function testParamsReturnsParameters()
    {
        $article = new Article(999);

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($article, ['id' => 999, 'attribs' => '{"foo":"var"}']);

        $this->assertEquals(new Registry(['foo' => 'var']), $article->params());
    }

    /**
     * table returns correct table instance.
     *
     * @return  void
     */
    public function testTableReturnsCorrectTableInstance()
    {
        $article = new Article();

        $this->assertInstanceOf('JTableContent', $article->table());
    }

    /**
     * validator returns validator variable.
     *
     * @return  void
     */
    public function testValidatorReturnsValidatorVariable()
    {
        $article = new Article();

        $reflectionClass = new \ReflectionClass($article);
        $reflectionProperty = $reflectionClass->getProperty('validator');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($article));

        $articleValidator = new ArticleValidator($article);

        $this->assertNotSame($articleValidator, $article->validator());

        $reflectionProperty->setValue($article, $articleValidator);

        $this->assertSame($articleValidator, $article->validator());
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_assets', JPATH_TEST_DATABASE.'/jos_assets.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_categories', JPATH_TEST_DATABASE.'/jos_categories.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_content', JPATH_TEST_DATABASE.'/jos_content.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }

    /**
     * Get a mock of the articles model returning specific items.
     *
     * @param   array  $items  Items returned
     *
     * @return  \PHPUnit_Framework_MockObject_MockObject
     */
    private function getArticlesModelMock(array $items = [])
    {
        $mock = $this->getMockBuilder('ArticlesModelMock')
            ->disableOriginalConstructor()
            ->setMethods(['getItems'])
            ->getMock();

        $mock->expects($this->once())
            ->method('getItems')
            ->willReturn($items);

        return $mock;
    }
}
