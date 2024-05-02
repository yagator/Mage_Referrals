<?php
declare(strict_types=1);

namespace Yagator\Referrals\Block\Referrals;

use Yagator\Referrals\Api\Data\ReferralInterface;
use Yagator\Referrals\Api\ReferralRepositoryInterface;

class Save extends \Magento\Customer\Block\Account\Customer
{
    /**
     * @var ReferralRepositoryInterface
     */
    protected $referralRepository;

    public function __construct(
        ReferralRepositoryInterface $referralRepository,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ){
        $this->referralRepository = $referralRepository;
        parent::__construct($context, $httpContext, $data);
    }

    /**
     * @return int
     */
    public function referralExists(): int
    {
        return (int)$this->getRequest()->getParam('referral_id', 0);
    }

    /**
     * @return ReferralInterface|null
     */
    public function getReferral(): ReferralInterface|null
    {
        $referral_id = $this->getRequest()->getParam('referral_id', 0);
        if (!$referral_id) {
            return null;
        }

        return $this->referralRepository->getById($referral_id);
    }

    /**
     * @return string
     */
    public function getFormAction(): string
    {
        return $this->getUrl('*/*/referralpost', ['_secure' => true]);
    }
}
