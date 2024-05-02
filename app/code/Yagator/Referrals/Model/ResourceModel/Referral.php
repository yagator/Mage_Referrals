<?php
declare(strict_types=1);

namespace Yagator\Referrals\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Referral extends AbstractDb
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('referral', 'entity_id');
    }
}
