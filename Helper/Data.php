<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-04 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-18 08:45:14
 */
namespace Magepow\Gdpr\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    protected $storeManager;
	
	protected $_url;
	
	protected $_pageFactory;
	
	protected $_filterProvider;

	const XML_PATH_RSGITECH_NEWS = 'gdpr/';
	
    public function __construct(
        Context $context,
		\Magento\Framework\Url $url,
		\Magento\Cms\Model\PageFactory $pageFactory,
		\Magento\Cms\Model\Template\FilterProvider $filterProvider,
		StoreManagerInterface $storeManager 
    )
    {
    	$this->storeManager = $storeManager;
		$this->_url = $url;
		$this->_pageFactory = $pageFactory;
		$this->_filterProvider = $filterProvider;
        parent::__construct($context);
    }

    public function getConfig($path, $store = null)
    {
        if ($store == null || $store == '') {
            $store = $this->storeManager->getStore()->getId();
        }
        $store = $this->storeManager->getStore($store);
        $config = $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $store);
        return $config;
    }
	
	public function getUrlBuilder($identifier){
		return $this->_url->getUrl($identifier);
	}
	
	public function getPageContent($identifier){
		$page = $this->_pageFactory->create()->load($identifier);
		return $this->_filterProvider->getPageFilter()->filter($page->getContent());
	}

}