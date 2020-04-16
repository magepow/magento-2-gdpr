<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-06-04 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-06-18 08:45:22
 */
namespace Magepow\Gdpr\Model\Config\Source;

class Align implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
			['value' => 'left', 'label' => __('Left')], 
			['value' => 'right', 'label' => __('Right')],
			['value' => 'center', 'label' => __('Center')]
		];
    }
}
