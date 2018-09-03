<?php

namespace Tests\Magium\MagentoEE114\Extractors;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\CustomerCheckout;
use Magium\Magento\Extractors\Customer\Order\BillingAddress;
use Magium\Magento\Extractors\Customer\Order\ItemList;
use Magium\Magento\Extractors\Customer\Order\ShippingAddress;
use Magium\Magento\Extractors\Customer\Order\Summary;
use Magium\Magento\Extractors\Checkout\OrderId;
use Magium\Magento\Navigators\Customer\AccountHome;
use Magium\Magento\Navigators\Customer\NavigateToOrder;

class CustomerOrderExtractorTest extends \Tests\Magium\Magento\Extractors\CustomerOrderExtractorTest
{

    public function setUp()
    {
        parent::setUp();
        $this->switchThemeConfiguration('Magium\Magento\Themes\MagentoEE114\ThemeConfiguration');
    }

}