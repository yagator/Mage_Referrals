<?php
declare(strict_types=1);

namespace Yagator\Referrals\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const STATUSES = [0 => 'pending', 1 => 'registered'];

    /**
     * @param int $status
     * @return string
     */
    public function getStatus(int $status): string
    {
        if (array_key_exists($status, self::STATUSES)){
            return self::STATUSES[$status];
        }

        return '';
    }
}
