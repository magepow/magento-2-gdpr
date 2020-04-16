<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-04 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-18 08:44:54
 */
namespace Magepow\Gdpr\Controller\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;
use Magento\Framework\App\ObjectManager;
use Magepow\Gdpr\Controller\Customer as CustomerController;

class Save extends CustomerController
{
    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;
	
	/**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var PhpCookieManager
     */
    private $cookieMetadataManager;

    private $registry;
    private $_quoteFactory;
    private $_contactFactory;


    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteFactory,
		\Magepow\Gdpr\Model\ResourceModel\Contact\CollectionFactory $contactFactory,
		\Magento\Framework\Registry $registry,
        CustomerRepository $customerRepository
        
    ) {
        $this->storeManager = $storeManager;
        $this->formKeyValidator = $formKeyValidator;
        $this->customerRepository = $customerRepository;
        $this->_quoteFactory = $quoteFactory;
        $this->_contactFactory = $contactFactory;
        $this->registry = $registry;
        parent::__construct($context, $customerSession);
    }
	
	/**
     * Retrieve cookie manager
     *
     * @deprecated 100.1.0
     * @return PhpCookieManager
     */
    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = ObjectManager::getInstance()->get(PhpCookieManager::class);
        }
        return $this->cookieMetadataManager;
    }

    /**
     * Retrieve cookie metadata factory
     *
     * @deprecated 100.1.0
     * @return CookieMetadataFactory
     */
    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = ObjectManager::getInstance()->get(CookieMetadataFactory::class);
        }
        return $this->cookieMetadataFactory;
    }

    /**
     * Save newsletter subscription preference action
     *
     * @return void|null
     */
    public function execute()
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $this->_redirect('customer/account/');
        }
		
        $customerId = $this->customerSession->getCustomerId();
		$customerEmail = $this->customerSession->getCustomer()->getEmail();
        if ($customerId === null) {
            $this->messageManager->addError(__('Something went wrong while saving your subscription.'));
        } else {
            try {
                if ((boolean)$this->getRequest()->getParam('remove_account', false)) {
					$this->registry->register('isSecureArea', true);
					$this->customerRepository->deleteById($customerId);
					
					$quoteCollection = $this->_quoteFactory->create()->addFieldToFilter('customer_email', $customerEmail);
					
					if(count($quoteCollection)>0){
						foreach($quoteCollection as $_quote){
							$_quote->delete();
						}
					}
					
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
                    $version = $productMetadata->getVersion();
                        if (version_compare($version, '2.2.2') >= 0) {
                        $contactCollection = $this->_contactFactory->create()->addFieldToFilter('email', $customerEmail);
    					
    					if(count($contactCollection)>0){
    						foreach($contactCollection as $_contact){
    							$_contact->delete();
    						}
                        }
                    }
                    $this->messageManager->addSuccess(__('We deleted your account.'));
					
					if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
						$metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
						$metadata->setPath('/');
						$this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
					}
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
		
        $this->_redirect('customer/account/login');
    }
}
