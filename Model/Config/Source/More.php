<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-04 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-18 08:45:30
 */
namespace Magepow\Gdpr\Model\Config\Source;

class More implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
			['value' => 'not-show', 'label' => __('Not Show')], 
			['value' => 'link', 'label' => __('Show as link')], 
			['value' => 'popup', 'label' => __('Show as popup')]
		];
    }
}
