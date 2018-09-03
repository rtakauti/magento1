<?php

namespace Magium\Util\Phpunit;

use Exception;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;

class MasterListener implements TestListener, MasterListenerInterface
{

    protected $replay = [];
    protected $listeners = [];
    protected $result;

    /**
     * @param $class
     * @return TestListener
     */

    public function getListener($class)
    {
        foreach ($this->listeners as $listener) {
            if (get_class($listener) == $class) {
                return $listener;
            }
        }
    }

    public function clear()
    {
        $this->replay = [];
        $this->listeners = [];
        $this->result = null;
    }

    public function addListener($listener)
    {
        // This pretty piece of code is to maintain compatibility between PHPUnit 5 and 6.
        if ($listener instanceof TestListener) {
            foreach ($this->listeners as $existingListener) {
                if (get_class($listener) == get_class($existingListener)) {
                    return;
                }
            }
            $this->listeners[] = $listener;
            foreach ($this->replay as $replay) {
                $this->play($replay['method'], $replay['args'], $listener);
            }
        }
    }

    public function play($method, $args, TestListener $instance = null)
    {
        if ($instance instanceof TestListener) {
            call_user_func_array([$instance, $method], $args);
        } else {
            foreach ($this->listeners as $listener) {
                call_user_func_array([$listener, $method], $args);
            }
        }
    }

    public function bindToResult(TestResult $result)
    {
        if ($this->result !== $result) {
            $this->result = $result;
            $result->addListener($this);
        }
    }

    public function addError(Test $test, Exception $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function addWarning(Test $test, Warning $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function addIncompleteTest(Test $test, Exception $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function addRiskyTest(Test $test, Exception $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function addSkippedTest(Test $test, Exception $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function startTestSuite(TestSuite $suite)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function endTestSuite(TestSuite $suite)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function startTest(Test $test)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function endTest(Test $test, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

}
