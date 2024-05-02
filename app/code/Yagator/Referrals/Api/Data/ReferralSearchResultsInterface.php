<?php
declare(strict_types=1);

namespace Yagator\Referrals\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ReferralSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Yagator\Referrals\Api\Data\ReferralInterface[]
     */
    public function getItems();

    /**
     * @param \Yagator\Referrals\Api\Data\ReferralInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
