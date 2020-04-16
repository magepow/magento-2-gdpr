<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-04 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-18 08:44:17
 */
namespace Magepow\Gdpr\Block\Customer;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;


class Button extends \Magento\Customer\Block\Account\Dashboard{
	
	public function getAction()
	{
	    return $this->getUrl('gdpr/customer/save');
	}
}