<?php

namespace Tests\Magium\Magento\Navigation;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Customer\NavigateAndLogin;
use Magium\Magento\Navigators\Customer\Account;
use Magium\WebDriver\WebDriver;

class CustomerNavigationTest extends AbstractMagentoTestCase
{

    public function testNavigationSucceedsWithoutHeader()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(NavigateAndLogin::ACTION)->login();
        $this->getNavigator(Account::NAVIGATOR)->navigateTo('My Orders');
        $theme = $this->getTheme();
        /* @var $theme \Magium\Magento\Themes\AbstractThemeConfiguration */
        $xpath = $this->get($theme->getCustomerThemeClass())->getAccountSectionHeaderXpath('My Orders');

        $this->assertElementDisplayed($xpath, WebDriver::BY_XPATH);
    }


    public function testNavigationSucceedsWithHeader()
    {

        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(NavigateAndLogin::ACTION)->login();
        $this->getNavigator(Account::NAVIGATOR)->navigateTo('My Orders', 'My Orders');
        $theme = $this->getTheme();
        /* @var $theme \Magium\Magento\Themes\AbstractThemeConfiguration */
        $xpath = $this->get($theme->getCustomerThemeClass())->getAccountSectionHeaderXpath('My Orders');
        $this->assertElementDisplayed($xpath, WebDriver::BY_XPATH);
    }

    public function testThrowsExceptionWithInvalidSectionTitle()
    {
        $this->setExpectedException('Facebook\WebDriver\Exception\WebDriverException');
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(NavigateAndLogin::ACTION)->login();
        $this->getNavigator(Account::NAVIGATOR)->navigateTo('boogers');
    }


    public function testThrowsExceptionWithInvalidSectionHeader()
    {
        $this->setExpectedException('Facebook\WebDriver\Exception\WebDriverException');
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(NavigateAndLogin::ACTION)->login();
        $this->getNavigator(Account::NAVIGATOR)->navigateTo('My Orders', 'boogers');
    }

}