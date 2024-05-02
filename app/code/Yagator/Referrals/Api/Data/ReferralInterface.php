<?php
declare(strict_types=1);

namespace Yagator\Referrals\Api\Data;

interface ReferralInterface
{
    const ENTITY_ID = 'entity_id';
    const FIRSTNAME = 'firstname';
    const LASTNAME = 'lastname';
    const EMAIL = 'email';
    const PHONE = 'phone';
    const STATUS = 'status';
    const CUSTOMER_ID = 'customer_id';

    /**
     * @return int
     */
    public function getEntityId(): int;

    /**
     * @param int $entity_id
     * @return ReferralInterface
     */
    public function setEntityId($entity_id): ReferralInterface;

    /**
     * @return string
     */
    public function getFirstname(): string;

    /**
     * @param string $firstname
     * @return ReferralInterface
     */
    public function setFirstname(string $firstname): ReferralInterface;

    /**
     * @return string
     */
    public function getLastname(): string;

    /**
     * @param string $lastname
     * @return ReferralInterface
     */
    public function setLastname(string $lastname): ReferralInterface;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @param string $email
     * @return ReferralInterface
     */
    public function setEmail(string $email): ReferralInterface;

    /**
     * @return string
     */
    public function getPhone(): string;

    /**
     * @param string $phone
     * @return ReferralInterface
     */
    public function setPhone(string $phone): ReferralInterface;

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @param int $status
     * @return ReferralInterface
     */
    public function setStatus(int $status): ReferralInterface;

    /**
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * @param int $customer_id
     * @return ReferralInterface
     */
    public function setCustomerId(int $customer_id): ReferralInterface;

}
