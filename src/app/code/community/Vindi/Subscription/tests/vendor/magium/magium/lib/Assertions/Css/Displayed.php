<?php

namespace Magium\Assertions\Css;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class Displayed extends \Magium\Assertions\Element\Displayed  implements SelectorAssertionInterface
{

    const ASSERTION = 'Css/Displayed';

    public function assertSelector($selector)
    {
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_CSS_SELECTOR);
        $this->assert();
    }

}
