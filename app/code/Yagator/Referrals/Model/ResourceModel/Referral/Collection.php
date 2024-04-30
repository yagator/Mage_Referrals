<?php

namespace Yagator\Referrals\Model\ResourceModel\Referral;

use Yagator\Referrals\Model\Referral as ReferralModel;
use Yagator\Referrals\Model\ResourceModel\Referral as ReferralResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ReferralModel::class, ReferralResourceModel::class);
    }
}
