<?php
/**
 * Copyright 2020 Adobe
 * All Rights Reserved.
 */

declare(strict_types=1);

namespace Magento\TwoFactorAuth\Block\Provider\Authy;

use Magento\Backend\Block\Template;

/**
 * @api
 */
class Auth extends Template
{
    /**
     * @inheritdoc
     */
    public function getJsLayout()
    {
        $this->jsLayout['components']['tfa-auth']['postUrl'] =
            $this->getUrl('*/*/authpost');

        $this->jsLayout['components']['tfa-auth']['tokenRequestUrl'] =
            $this->getUrl('*/*/token');

        $this->jsLayout['components']['tfa-auth']['oneTouchUrl'] =
            $this->getUrl('*/*/onetouch');

        $this->jsLayout['components']['tfa-auth']['verifyOneTouchUrl'] =
            $this->getUrl('*/*/verifyonetouch');

        $this->jsLayout['components']['tfa-auth']['successUrl'] =
            $this->getUrl($this->_urlBuilder->getStartupPageUrl());

        return parent::getJsLayout();
    }
}
