<?php
declare(strict_types=1);

namespace Yagator\Referrals\Model\ResourceModel\Referral;

use Yagator\Referrals\Model\Referral as ReferralModel;
use Yagator\Referrals\Model\ResourceModel\Referral as ReferralResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ReferralModel::class, ReferralResourceModel::class);
    }
}
