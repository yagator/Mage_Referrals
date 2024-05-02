<?php
declare(strict_types=1);

namespace Yagator\Referrals\Api;

use Yagator\Referrals\Api\Data\ReferralInterface;
use Yagator\Referrals\Api\Data\ReferralSearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;

interface ReferralRepositoryInterface
{
    /**
     * @param ReferralInterface $referral
     * @return ReferralInterface
     */
    public function save(ReferralInterface $referral): ReferralInterface;

    /**
     * @param int $referralId
     * @return ReferralInterface
     */
    public function getById($referralId): ReferralInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return ReferralSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param ReferralInterface $referral
     * @return bool
     */
    public function delete(ReferralInterface $referral): bool;

    /**
     * @param int $referralId
     * @return bool
     */
    public function deleteById($referralId): bool   ;

}
