<?php

namespace Magium\Util\Log;

use Exception;
use Magium\Assertions\AbstractAssertion;
use Magium\Util\Phpunit\MasterListenerAware;
use Magium\Util\Phpunit\MasterListenerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestListener;


class LoggerPHPUnit5 extends \Zend\Log\Logger implements TestListener, MasterListenerAware, LoggerInterface
{

    protected $status = self::STATUS_PASSED;

    protected $testName = self::NAME_DEFAULT;

    protected $testId;

    protected $invokedTest;

    protected $selectorConfig = null;

    protected static $testRunId;

    public function setMasterListener(MasterListenerInterface $listener)
    {
        $listener->addListener($this);
    }

    public function addCharacteristic($type, $value)
    {
        $this->info(
            sprintf('Set %s to %s', $type, $value),
            [
                'type'              => 'characteristic',
                'characteristic'    => $type,
                'value'             => $value
            ]
        );
    }

    public function getInvokedTest()
    {
        return $this->invokedTest;
    }

    public function setTestName($name)
    {
        $this->testName = $name;
    }

    public function setTestStatus($status)
    {
        $this->status = $status;
    }

    public function setTestId($testId)
    {
        $this->testId = $testId;
    }

    public function logStep($stepId)
    {
        $this->log($stepId, $this->createExtra(['type' => 'step']));
    }

    public function logAssertionSuccess(AbstractAssertion $assertion, array $extra)
    {
        $extra = array_merge($extra, ['type' => 'assertion', 'result' => self::STATUS_PASSED]);
        $this->info(get_class($assertion) . ' - passed', $this->createExtra($extra));
    }

    public function logAssertionFailure(Exception $e, AbstractAssertion $assertion, array $extra)
    {
        $extra = array_merge($extra, ['type' => 'assertion', 'result' => self::STATUS_FAILED, 'stack_trace' => $e->getTrace()]);
        $this->err(get_class($assertion) . ' - ' . $e->getMessage(), $this->createExtra($extra));
    }

    public function createExtra($includeArray = [])
    {
        $defaultArray = [
            'type'      => 'message',
            'status'    => $this->status,
            'name'     => $this->testName,
            'test_id'    => $this->testId,
            'test_run_id' => $this->getTestRunId()
        ];

        if ($this->selectorConfig) {
            $defaultArray = array_merge($this->selectorConfig, $defaultArray);
        }

        return array_merge($defaultArray, $includeArray);
    }

    public function getTestRunId()
    {
        if (!self::$testRunId) {
            // See https://github.com/ircmaxell/RandomLib/issues/55
            if (function_exists('random_bytes')) {
                $unique = uniqid(substr(bin2hex(random_bytes(64)), 0, 64));

            } else if (function_exists('openssl_random_pseudo_bytes')) {
                $unique = uniqid(openssl_random_pseudo_bytes(64));
            } else {
                $unique = uniqid('', true);
            }
            self::$testRunId = $unique;
        }

        return self::$testRunId;
    }


    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->setTestStatus(self::STATUS_FAILED);
        $this->notice($e->getMessage(), $this->createExtra(['trace' => $e->getTraceAsString()]));
    }

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->setTestStatus(self::STATUS_FAILED);
        $this->notice($e->getMessage(), $this->createExtra(['trace' => $e->getTraceAsString()]));
    }

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->setTestStatus(self::STATUS_INCOMPLETE);
        $this->notice($e->getMessage(), $this->createExtra());
    }

    public function addRiskyTest(\PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // ignore
    }

    public function addSkippedTest(\PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        self::setTestStatus(self::STATUS_SKIPPED);
        $this->notice($e->getMessage(), $this->createExtra());
    }

    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        // ignore
    }

    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        $this->testName = self::NAME_DEFAULT;
    }

    public function startTest(\PHPUnit_Framework_Test $test)
    {
        if ($test instanceof TestCase) {
            if (!$this->testName) {
                $this->setTestName(get_class($test) . '::' . $test->getName());
            }
            $this->invokedTest = get_class($test) . '::' . $test->getName();
            $this->setTestStatus(self::STATUS_PASSED);
        }

    }

    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        $this->info(sprintf('Test completed with status: %s', $this->status), $this->createExtra());
        $this->testName = self::NAME_DEFAULT;
    }


}
