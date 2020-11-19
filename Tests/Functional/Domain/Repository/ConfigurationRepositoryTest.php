<?php

declare(strict_types=1);

namespace AOE\Crawler\Tests\Functional\Domain\Repository;

/*
 * (c) 2020 AOE GmbH <dev@aoe.com>
 *
 * This file is part of the TYPO3 Crawler Extension.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use AOE\Crawler\Domain\Model\Configuration;
use AOE\Crawler\Domain\Repository\ConfigurationRepository;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class ConfigurationRepositoryTest
 */
class ConfigurationRepositoryTest extends FunctionalTestCase
{
    private const PAGE_WITHOUT_CONFIGURATIONS = 11;

    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/crawler'];

    /**
     * @var array
     */
    protected $coreExtensionsToLoad = ['cms', 'core', 'frontend', 'version', 'lang', 'fluid'];

    /**
     * @var ConfigurationRepository
     */
    protected $subject;

    /**
     * Creates the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->subject = $objectManager->get(ConfigurationRepository::class);
        $this->importDataSet(__DIR__ . '/../../Fixtures/tx_crawler_configuration.xml');
        $this->importDataSet(__DIR__ . '/../../Fixtures/pages.xml');
    }

    /**
     * @test
     */
    public function getCrawlerConfigurationRecordsFromRootLineReturnsEmptyArray(): void
    {
        self::assertCount(
            0,
            $this->subject->getCrawlerConfigurationRecordsFromRootLine(self::PAGE_WITHOUT_CONFIGURATIONS)
        );
    }

    /**
     * @test
     */
    public function getCrawlerConfigurationRecordsFromRootLineReturnsObjects(): void
    {
        $configurations = $this->subject->getCrawlerConfigurationRecordsFromRootLine(4);

        self::assertEquals(
            2,
            $configurations->count()
        );

        /** @var Configuration $configuration */
        foreach ($configurations as $configuration) {
            self::assertContains($configuration->getUid(), [1, 8]);
        }
    }

    /**
     * @test
     */
    public function getCrawlerConfigurationRecords(): void
    {
        $configurations = $this->subject->getCrawlerConfigurationRecords();

        self::assertEquals(
            4,
            $configurations->count()
        );

        /** @var Configuration $configuration */
        foreach ($configurations as $configuration) {
            self::assertContains($configuration->getUid(), [1, 5, 6, 8]);
        }
    }
}
