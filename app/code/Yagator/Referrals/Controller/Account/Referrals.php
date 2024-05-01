<?php

namespace Yagator\Referrals\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;

class Referrals extends \Magento\Customer\Controller\AbstractAccount implements HttpGetActionInterface
{

    /**
     * @var PageFactory
     */
    private $page_factory;

    public function __construct(
        PageFactory $page_factory,
        Context $context
    ){
        $this->page_factory = $page_factory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        return $this->page_factory->create();
    }
}
