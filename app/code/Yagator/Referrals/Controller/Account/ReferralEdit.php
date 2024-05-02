<?php

namespace Yagator\Referrals\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Yagator\Referrals\Api\ReferralRepositoryInterface;

class ReferralEdit extends \Magento\Customer\Controller\AbstractAccount implements HttpGetActionInterface
{
    /**
     * @var ReferralRepositoryInterface
     */
    protected $referralRepository;
    /**
     * @var ManagerInterface
     */
    protected $messageManager;
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var PageFactory
     */
    private $page_factory;

    public function __construct(
        ReferralRepositoryInterface $referralRepository,
        ManagerInterface $messageManager,
        CustomerSession $customerSession,
        PageFactory $page_factory,
        Context $context
    ){
        $this->referralRepository = $referralRepository;
        $this->messageManager = $messageManager;
        $this->customerSession = $customerSession;
        $this->page_factory = $page_factory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $referral_id = $this->getRequest()->getParam('referral_id', 0);
            if ($referral_id) {
                $referral = $this->referralRepository->getById($referral_id);
                if ($referral->getCustomerId() != $this->customerSession->getCustomerId()) {
                    $this->messageManager->addErrorMessage('You are not allowed to edit this referral.');
                    $this->_redirect('*/*/referrals');
                }
            }
        } catch (NoSuchEntityException $e) {
            $message = $e->getMessage();
            $this->messageManager->addErrorMessage($message);
            $this->_redirect('*/*/referrals');
        }

        return $this->page_factory->create();
    }
}
