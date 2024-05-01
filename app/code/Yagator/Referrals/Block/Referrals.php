<?php

namespace Yagator\Referrals\Block;

use Yagator\Referrals\Api\Data\ReferralInterface;
use Yagator\Referrals\Api\ReferralRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\Search\SearchCriteriaInterface as SearchCriteria;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;


class Referrals extends \Magento\Customer\Block\Account\Customer
{
    private $referralRepository;
    private $customerSession;
    private $searchCriteria;
    private $filterGroup;
    private $filter;
    private $sortOrder;

    public function __construct(
        ReferralRepositoryInterface $referralRepository,
        CustomerSession $customerSession,
        SearchCriteria $searchCriteria,
        FilterGroup $filterGroup,
        Filter $filter,
        SortOrder $sortOrder,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ){
        $this->referralRepository = $referralRepository;
        $this->customerSession = $customerSession;
        $this->searchCriteria = $searchCriteria;
        $this->filterGroup = $filterGroup;
        $this->filter = $filter;
        $this->sortOrder = $sortOrder;
        parent::__construct($context, $httpContext, $data);
    }

    public function getReferrals(){
        $customer = $this->customerSession->getCustomer();
        $customer_id = $customer->getId();

        $filter = $this->filter
            ->setField(ReferralInterface::CUSTOMER_ID)
            ->setValue($customer_id)
            ->setConditionType('eq');
        $this->searchCriteria
            ->setFilterGroups([$this->filterGroup->setFilters([$filter])])
            ->setSortOrders([$this->sortOrder->setField(ReferralInterface::ENTITY_ID)->setDirection('ASC')])
            ->setPageSize(1000)
            ->setCurrentPage(1);

        return $this->referralRepository->getList($this->searchCriteria);
    }
}
