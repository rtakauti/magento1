<?php

namespace Tests\Magento18\Checkout;

class GuestCheckoutTest extends \Tests\Magium\Magento\Checkout\GuestCheckoutTest
{

    protected function setUp()
    {
        parent::setUp();
        $this->switchThemeConfiguration('Magium\Magento\Themes\Magento18\ThemeConfiguration');
    }

}