<?php
declare(strict_types=1);

namespace Yagator\Referrals\Block;

use Magento\Framework\Exception\InputException;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\Search\SearchCriteriaInterface as SearchCriteria;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Yagator\Referrals\Api\Data\ReferralInterface;
use Yagator\Referrals\Api\ReferralRepositoryInterface;
use Yagator\Referrals\Helper\Data;


class Referrals extends \Magento\Customer\Block\Account\Customer
{
    const DEFAULT_PAGE_SIZE = 20;
    const DEFAULT_PAGE = 1;
    const SIZES_OPTIONS = [10, 20, 50];

    /**
     * @var ReferralRepositoryInterface
     */
    protected $referralRepository;
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var SearchCriteria
     */
    protected $searchCriteria;
    /**
     * @var FilterGroup
     */
    protected $filterGroup;
    /**
     * @var FilterGroup
     */
    protected $filterSearch;
    /**
     * @var Filter
     */
    protected $filter;
    /**
     * @var Filter
     */
    protected $filterEmail;
    /**
     * @var SortOrder
     */
    protected $sortOrder;
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var
     */
    protected $request;

    private $total_referrals = 0;

    public function __construct(
        ReferralRepositoryInterface $referralRepository,
        CustomerSession $customerSession,
        SearchCriteria $searchCriteria,
        FilterGroup $filterGroup,
        FilterGroup $filterSearch,
        Filter $filter,
        Filter $filterEmail,
        SortOrder $sortOrder,
        Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ){
        $this->referralRepository = $referralRepository;
        $this->customerSession = $customerSession;
        $this->searchCriteria = $searchCriteria;
        $this->filterGroup = $filterGroup;
        $this->filterSearch = $filterSearch;
        $this->filter = $filter;
        $this->filterEmail = $filterEmail;
        $this->sortOrder = $sortOrder;
        $this->helper = $helper;
        parent::__construct($context, $httpContext, $data);
    }

    /**
     * @return mixed
     * @throws InputException
     */
    public function getReferrals()
    {
        $customer = $this->customerSession->getCustomer();
        $customer_id = $customer->getId();
        $current_page = $this->getCurrentPage();
        $page_size = $this->currentPageSize();
        $search = $this->getCurrentSearch();

        $filter = $this->filter
            ->setField(ReferralInterface::CUSTOMER_ID)
            ->setValue($customer_id)
            ->setConditionType('eq');
        if ($search != ''){
            $filter_email = $this->filterEmail
                ->setField(ReferralInterface::EMAIL)
                ->setValue('%' . $search . '%')
                ->setConditionType('like');
            $this->searchCriteria->setFilterGroups([
                $this->filterGroup->setFilters([$filter]),
                $this->filterSearch->setFilters([$filter_email])
            ]);
        } else {
            $this->searchCriteria->setFilterGroups([$this->filterGroup->setFilters([$filter])]);
        }

        $this->searchCriteria
            ->setSortOrders([$this->sortOrder->setField(ReferralInterface::ENTITY_ID)->setDirection('ASC')])
            ->setPageSize($page_size)
            ->setCurrentPage($current_page);

        $list = $this->referralRepository->getList($this->searchCriteria);
        $this->total_referrals = $list->getTotalCount();

        return $list->getItems();
    }

    /**
     * @param $status
     * @return string
     */
    public function getStatus($status)
    {
        return $this->helper->getStatus((int)$status);
    }

    public function getTotalReferrals(){
        return $this->total_referrals;
    }

    public function getTotalPages()
    {
        return ceil($this->total_referrals/$this->currentPageSize());
    }

    public function getNewRefUrl($id=null)
    {
        return $this->_urlBuilder->getUrl('*/*/referraledit', ['referral_id' => $id]);
    }

    public function getDelRefUrl($id)
    {
        return $this->_urlBuilder->getUrl('*/*/referraldelete', ['referral_id' => $id]);
    }

    public function buildUrlParams($page=1, $size=self::DEFAULT_PAGE_SIZE, $search='')
    {
        return $this->_urlBuilder->getUrl(
            '*/*/*',
            ['page' => $page, 'page_size' => $size, 'search' => $search]
        );
    }

    public function getSizesOptions()
    {
        return self::SIZES_OPTIONS;
    }

    public function currentPageSize()
    {
        return (int)$this->_request->getParam('page_size', self::DEFAULT_PAGE_SIZE);
    }

    public function getCurrentPage()
    {
        return $this->_request->getParam('page', self::DEFAULT_PAGE);
    }

    public function getCurrentSearch()
    {
        return $this->_request->getParam('search', '');
    }

    public function getPrevPage()
    {
        return ((int)$this->getCurrentPage() - 1);
    }

    public function hasPrevPage()
    {
        return ($this->getPrevPage() > 0);
    }

    public function getNextPage()
    {
        return ((int)$this->getCurrentPage() + 1);
    }

    public function hasNextPage()
    {
        return ($this->getNextPage() <= $this->getTotalPages());
    }
}
