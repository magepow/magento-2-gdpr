<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-04 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-18 08:46:24
 */
namespace Magepow\Gdpr\Observer;

use Magento\Customer\Model\AuthenticationInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class CheckUserLoginObserver implements ObserverInterface
{
    /**
     * @var \Magepow\Gdpr\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $_actionFlag;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $_session;

    /**
     * Customer data
     *
     * @var \Magento\Customer\Model\Url
     */
    protected $_customerUrl;

    /**
     * @param \Magepow\Gdpr\Helper\Data $helper
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\Session\SessionManagerInterface $customerSession
     * @param CaptchaStringResolver $captchaStringResolver
     * @param \Magento\Customer\Model\Url $customerUrl
     */
    public function __construct(
        \Magepow\Gdpr\Helper\Data $helper,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Session\SessionManagerInterface $customerSession,
        \Magento\Customer\Model\Url $customerUrl
    ) {
        $this->_helper = $helper;
        $this->_actionFlag = $actionFlag;
        $this->messageManager = $messageManager;
        $this->_session = $customerSession;
        $this->_customerUrl = $customerUrl;
    }

    /**
     * Check captcha on user login page
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @throws NoSuchEntityException
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
	if($this->_helper->getConfigModule('general/enabled')){
        $controller = $observer->getControllerAction();
        $loginParams = $controller->getRequest()->getPost('login');

		if ($this->_helper->getConfigModule('login/active') 
			&& !isset($loginParams['accept_gdpr'])) {
			
			$this->messageManager->addError(__('You do not agree with the storage and handling of your data by this website.'));
			$this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
			$beforeUrl = $this->_session->getBeforeAuthUrl();
			$url = $beforeUrl ? $beforeUrl : $this->_customerUrl->getLoginUrl();
			$controller->getResponse()->setRedirect($url);
		}
	}
        return;
    }
}
