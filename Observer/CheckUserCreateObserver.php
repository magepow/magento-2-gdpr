<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-04 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-18 08:46:17
 */
namespace Magepow\Gdpr\Observer;

use Magento\Framework\Event\ObserverInterface;

class CheckUserCreateObserver implements ObserverInterface
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
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlManager;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * @param \Magepow\Gdpr\Helper\Data $helper
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\Session\SessionManagerInterface $session
     * @param \Magento\Framework\UrlInterface $urlManager
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     * @param CaptchaStringResolver $captchaStringResolver
     */
    public function __construct(
        \Magepow\Gdpr\Helper\Data $helper,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Framework\UrlInterface $urlManager,
        \Magento\Framework\App\Response\RedirectInterface $redirect
    ) {
        $this->_helper = $helper;
        $this->_actionFlag = $actionFlag;
        $this->messageManager = $messageManager;
        $this->_session = $session;
        $this->_urlManager = $urlManager;
        $this->redirect = $redirect;
    }

    /**
     * Check Captcha On User Login Page
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
	if($this->_helper->getConfigModule('general/enabled')){
		$controller = $observer->getControllerAction();
		$data = $controller->getRequest()->getPost();
		
        if ($this->_helper->getConfigModule('register/active') 
		&& !isset($data['accept_gdpr'])) {

			$this->messageManager->addError(__('You do not agree with the storage and handling of your data by this website.'));
			$this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
			$this->_session->setCustomerFormData($controller->getRequest()->getPostValue());
			$url = $this->_urlManager->getUrl('*/*/create', ['_nosecret' => true]);
			$controller->getResponse()->setRedirect($this->redirect->error($url));

        }
	}
        return;
    }
}
