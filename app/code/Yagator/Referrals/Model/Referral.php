<?php

namespace Yagator\Referrals\Model;

use Yagator\Referrals\Api\Data\ReferralInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Referral extends AbstractExtensibleModel implements ReferralInterface, IdentityInterface
{
    const CACHE_TAG = 'yagator_referrals';
    const PENDING = 0;
    const REGISTERED = 1;

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(ResourceModel\Referral::class);
    }


    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->getData(self::ENTITY_ID);
    }


    /**
     * @param $entity_id
     * @return ReferralInterface
     */
    public function setEntityId($entity_id): ReferralInterface
    {
        return $this->setData(self::ENTITY_ID, $entity_id);
    }


    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->getData(self::FIRSTNAME);
    }


    /**
     * @param string $firstname
     * @return ReferralInterface
     */
    public function setFirstname(string $firstname): ReferralInterface
    {
        return $this->setData(self::FIRSTNAME, $firstname);
    }


    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->getData(self::LASTNAME);
    }


    /**
     * @param string $lastname
     * @return ReferralInterface
     */
    public function setLastname(string $lastname): ReferralInterface
    {
        return $this->setData(self::LASTNAME, $lastname);
    }


    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->getData(self::EMAIL);
    }


    /**
     * @param string $email
     * @return ReferralInterface
     */
    public function setEmail(string $email): ReferralInterface
    {
        return $this->setData(self::EMAIL, $email);
    }


    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->getData(self::PHONE);
    }


    /**
     * @param string $phone
     * @return ReferralInterface
     */
    public function setPhone(string $phone): ReferralInterface
    {
        return $this->setData(self::PHONE, $phone);
    }


    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->getData(self::STATUS);
    }


    /**
     * @param int $status
     * @return ReferralInterface
     */
    public function setStatus(int $status): ReferralInterface
    {
        return $this->setData(self::STATUS, $status);
    }


    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->getData(self::CUSTOMER_ID);
    }


    /**
     * @param int $customer_id
     * @return ReferralInterface
     */
    public function setCustomerId(int $customer_id): ReferralInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customer_id);
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getEntityId()];
    }
}
