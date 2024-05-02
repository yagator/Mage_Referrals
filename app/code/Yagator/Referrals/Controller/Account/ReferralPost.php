<?php

namespace Yagator\Referrals\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Mail\Template\TransportBuilder;
use Yagator\Referrals\Api\ReferralRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Yagator\Referrals\Model\ReferralFactory;
use Yagator\Referrals\Model\Referral;

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
    protected $transportBuilder;
    protected $storeManager;

    /**
     * @var PageFactory
     */
    private $page_factory;

    public function __construct(
        ReferralRepositoryInterface $referralRepository,
        ReferralFactory $referralFactory,
        ManagerInterface $messageManager,
        CustomerSession $customerSession,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        PageFactory $page_factory,
        Context $context
    ){
        $this->referralRepository = $referralRepository;
        $this->referralFactory = $referralFactory;
        $this->messageManager = $messageManager;
        $this->customerSession = $customerSession;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
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
                    ->setStatus(Referral::PENDING)
                    ->setCustomerId($this->customerSession->getCustomerId());
            }
            $email = $this->getRequest()->getParam('email');
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
                $this->messageManager->addErrorMessage('Email has a wrong format');
                $this->_redirect('*/*/referrals');
                return;
            }
            $referral
                ->setFirstname($this->getRequest()->getParam('firstname'))
                ->setLastname($this->getRequest()->getParam('lastname'))
                ->setEmail($email)
                ->setPhone($this->getRequest()->getParam('phone'));
            try {
                $referral = $this->referralRepository->save($referral);
                $this->messageManager->addSuccessMessage('Referral saved successfully.');
                if (!$referral_id) {
                    $this->_sendMail($referral);
                }
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

    private function _sendMail(Referral $referral){
        $recipient_address = $referral->getEmail();
        $recipient_name = $referral->getFirstname();
        $from_address = [
            'name' =>  $this->customerSession->getCustomerData()->getFirstname(),
            'email' => $this->customerSession->getCustomerData()->getEmail()
        ];
        $vars = [
            'firstname' => $referral->getFirstname(),
            'customerName' => $this->customerSession->getCustomerData()->getFirstname(),
            'validate_link' => $this->_url->getUrl('*/*/referralvalidate', ['referral_id' => $referral->getEntityId()])
        ];
        $this->transportBuilder->setTemplateIdentifier(
            'new_referral_registered', \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId()
            ])->setTemplateVars($vars)->setFromByScope($from_address)->addTo($recipient_address, $recipient_name);
        $transport = $this->transportBuilder->getTransport();

        try {
            $transport->sendMessage();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }
}
