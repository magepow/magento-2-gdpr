<?php

/**
 * @Author: nguyen
 * @Date:   2021-02-04 08:45:07
 * @Last Modified by:   nguyen
 * @Last Modified time: 2021-02-04 09:27:44
 */

namespace Magepow\Gdpr\Controller\Index;

use Magento\Framework\Controller\ResultFactory; 

class Index extends \Magento\Framework\App\Action\Action
{
    
    /**
     * Index constructor.
     *
     * @param \Magepow\Gdpr\Helper\Data $helper
     */
	
	protected $_helper;    
    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magepow\Gdpr\Helper\Data $helper
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_helper 			  = $helper;
    }

    public function execute()
    {
    	if ($this->getRequest()->isAjax()) {
	        $this->_view->loadLayout();
	        $identifier = $this->_helper->getConfigModule('cookie_restriction/identifier');
		 	$response   = $this->_helper->getPageContent($identifier);
		 	if(!$response) $response = __('Page %1 not found or empty', $identifier);
		    $this->getResponse()->setBody($response);
	    }else {
	        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
	        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
	        return $resultRedirect;
	    }
    }
}
