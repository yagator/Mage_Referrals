<?php

namespace Yagator\Referrals\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Yagator\Referrals\Api\ReferralRepositoryInterface;
use Yagator\Referrals\Model\ReferralFactory;

class ReferralPost extends \Magento\Customer\Controller\AbstractAccount implements HttpPostActionInterface
{
    /**
     * @var ReferralRepositoryInterface
     */
    protected $referralRepository;
    /**
     * @var ReferralFactory
     */
    protected $referralFactory;
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
        ReferralFactory $referralFactory,
        ManagerInterface $messageManager,
        CustomerSession $customerSession,
        PageFactory $page_factory,
        Context $context
    ){
        $this->referralRepository = $referralRepository;
        $this->referralFactory = $referralFactory;
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
            $referral = $this->referralFactory->create();
            if ($referral_id) {
                $referral = $this->referralRepository->getById($referral_id);
                if ($referral->getCustomerId() != $this->customerSession->getCustomerId()) {
                    $this->messageManager->addErrorMessage('You are not allowed to edit this referral.');
                    $this->_redirect('*/*/referrals');
                    return;
                }
            } else {
                $referral
                    ->setStatus(0)
                    ->setCustomerId($this->customerSession->getCustomerId());
            }
            $referral
                ->setFirstname($this->getRequest()->getParam('firstname'))
                ->setLastname($this->getRequest()->getParam('lastname'))
                ->setEmail($this->getRequest()->getParam('email'))
                ->setPhone($this->getRequest()->getParam('phone'));
            try {
                $this->referralRepository->save($referral);
                $this->messageManager->addSuccessMessage('Referral saved successfully.');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_redirect('*/*/referraledit');
            }
        } catch (NoSuchEntityException $e) {
            $message = $e->getMessage();
            $this->messageManager->addErrorMessage($message);
            $this->_redirect('*/*/referrals');
        }

        $this->_redirect('*/*/referrals');
        return null;
    }
}
