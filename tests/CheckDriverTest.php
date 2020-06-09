<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers \AmazeeIO\Health\CheckDriver
 */
class CheckDriverTest extends TestCase
{

    /**
     * @test
     */
    public function it_should_run_applicable_checks()
    {
        $checkDriver = new \AmazeeIO\Health\CheckDriver();
        $checkDriver->registerCheck($this->generateApplicableCheck());
        $checkDriver->runChecks();
    }

    /**
     * @test
     */
    public function it_should_ignore_nonapplicable_checks()
    {
        $checkDriver = new \AmazeeIO\Health\CheckDriver();
        $checkDriver->registerCheck($this->generateNonApplicableCheck());
        $checkDriver->runChecks();
    }

    protected function generateApplicableCheck()
    {
        $check = $this->createMock(\AmazeeIO\Health\Check\CheckInterface::class);
        $check->expects($this->atLeastOnce())
          ->method('appliesInCurrentEnvironment')
          ->willReturn(true);
        $check->expects($this->once())
          ->method('pass');
        return $check;
    }

    protected function generateNonApplicableCheck()
    {
        $check = $this->createMock(\AmazeeIO\Health\Check\CheckInterface::class);
        $check->expects($this->atLeastOnce())
          ->method('appliesInCurrentEnvironment')
          ->willReturn(false);
        $check->expects($this->never())
          ->method('pass');
        return $check;
    }
}