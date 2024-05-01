<?php

namespace Yagator\Referrals\Model;

use Magento\Framework\Exception\AlreadyExistsException;
use Yagator\Referrals\Api\Data\ReferralSearchResultsInterface;
use Yagator\Referrals\Api\Data\ReferralSearchResultsInterfaceFactory;
use Yagator\Referrals\Api\Data\ReferralInterface;
use Yagator\Referrals\Api\Data\ReferralInterfaceFactory;
use Yagator\Referrals\Api\ReferralRepositoryInterface;
use Yagator\Referrals\Model\ReferralFactory;
use Yagator\Referrals\Model\ResourceModel\Referral as ResourceModelReferral;
use Yagator\Referrals\Model\ResourceModel\Referral\CollectionFactory as ReferralCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class ReferralRepository implements ReferralRepositoryInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var ResourceModelReferral
     */
    private $resource;
    /**
     * @var ReferralCollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ReferralFactory
     */
    private $referralFactory;
    /**
     * @var ReferralInterfaceFactory
     */
    private $referralInterfaceFactory;
    /**
     * @var ReferralSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        ResourceModelReferral  $resource,
        ReferralCollectionFactory $collectionFactory,
        ReferralFactory $referralFactory,
        ReferralInterfaceFactory $referralInterfaceFactory,
        ReferralSearchResultsInterfaceFactory $searchResultsFactory
    ){
        $this->collectionProcessor = $collectionProcessor;
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->referralFactory = $referralFactory;
        $this->referralInterfaceFactory = $referralInterfaceFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param ReferralInterface $referral
     * @return ReferralInterface
     */
    public function save(ReferralInterface $referral): ReferralInterface
    {
        try {
            $this->resource->save($referral);
        } catch (AlreadyExistsException $e) {
            throw new CouldNotSaveException('Referral already exists: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new LocalizedException('Unable to save referral: ' . $e->getMessage(), $e->getCode());
        }

        return $referral;
    }

    /**
     * @param $referralId
     * @return ReferralInterface
     */
    public function getById($referralId): ReferralInterface
    {
        $referral = $this->referralFactory->create();
        $this->resource->load($referral, $referralId);
        if (!$referral->getId()) {
            throw new NoSuchEntityException(__('Referral with the "%1" ID doesn\'t exist..', $referralId));
        }

        return $referral;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @param ReferralInterface $referral
     * @return bool
     */
    public function delete(ReferralInterface $referral): bool
    {
        try {
            $this->resource->delete($referral);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Unable to delete referral: %1', $e->getMessage()));
        }

        return true;
    }

    /**
     * @param $referralId
     * @return bool
     */
    public function deleteById($referralId): bool
    {
        return $this->delete($this->getById($referralId));
    }
}
