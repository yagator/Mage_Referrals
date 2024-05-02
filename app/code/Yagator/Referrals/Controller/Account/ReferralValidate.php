<?php
declare(strict_types=1);

namespace Yagator\Referrals\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Yagator\Referrals\Api\ReferralRepositoryInterface;
use Yagator\Referrals\Model\Referral;

class ReferralValidate extends \Magento\Customer\Controller\AbstractAccount implements HttpGetActionInterface
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
     * @var PageFactory
     */
    private $page_factory;
    /**
     * @var CustomerSession
     */
    protected $customerSession;

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
        $referral_id = $this->getRequest()->getParam('referral_id');
        try {
            $referral = $this->referralRepository->getById($referral_id);
            if ($referral->getEntityId()) {
                $referral->setStatus(Referral::REGISTERED);
                try {
                    $this->referralRepository->save($referral);
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
        } catch (NoSuchEntityException $e) {
            $message = $e->getMessage();
            $this->messageManager->addErrorMessage($message);
        }

        return $this->page_factory->create();
    }
}
