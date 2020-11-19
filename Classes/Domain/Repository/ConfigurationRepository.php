<?php

declare(strict_types=1);

namespace AOE\Crawler\Domain\Repository;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class ConfigurationRepository
 */
class ConfigurationRepository extends Repository
{
    /**
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getCrawlerConfigurationRecords()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIncludeDeleted(false);
        return $query->execute();
    }

    /**
     * Traverses up the rootline of a page and fetches all crawler records.
     */

    /**
     * @param int $pageId
     * @return array|QueryInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function getCrawlerConfigurationRecordsFromRootLine(int $pageId)
    {
        $pageIdsInRootLine = [];
        $rootLine = BackendUtility::BEgetRootLine($pageId);

        foreach ($rootLine as $pageInRootLine) {
            $pageIdsInRootLine[] = (int) $pageInRootLine['uid'];
        }

        if (empty($pageIdsInRootLine)) {
            return [];
        }

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIncludeDeleted(false);
        return $query->matching(
            $query->in('pid', $pageIdsInRootLine)
        )->execute();
    }
}
