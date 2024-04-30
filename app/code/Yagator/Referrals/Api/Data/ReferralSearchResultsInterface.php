<?php

namespace Yagator\Referrals\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
interface ReferralSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return mixed
     */
    public function getItems();

    /**
     * @param array $items
     * @return mixed
     */
    public function setItems(array $items);
}
