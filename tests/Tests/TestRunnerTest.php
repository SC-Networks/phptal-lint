<?php
/**
 * Add a LICENSE-text here someday
 */
namespace Tests;

use PHPUnit\Framework\TestCase;
use Scn\PhpTalLint\TestRunner;

/**
 * Class TestRunnerTest
 * @package Tests
 */
class TestRunnerTest extends TestCase
{

    /**
     * @var TestRunner
     */
    private $subject;

    public function setUp()
    {
        $this->subject = new TestRunner();
    }

    public function testRunWithEmptyFileset()
    {
        ob_start();
        $this->subject->run([]);
        $out = ob_get_clean();
        static::assertStringStartsWith(sprintf('Linting %1$d file(s)...%2$s%2$s', 0, PHP_EOL), $out);
        static::assertStringEndsWith(sprintf('%1$sOK%1$s', PHP_EOL), $out);
    }

    public function testRunWithSingleNonExistingFile()
    {
        $filename = '/some/random/file/which/should/exist/nowhere';

        ob_start();
        $this->subject->run([$filename]);
        $out = ob_get_clean();
        static::assertStringStartsWith(sprintf('Linting %1$d file(s)...%2$s%2$s', 1, PHP_EOL), $out);
        static::assertContains('The following errors have been recorded', $out);
        static::assertContains(sprintf('%1$s', $filename), $out);
    }

    public function testRunWithSingleExistingFile()
    {
        $filename = __DIR__ . '/../files/ok.html';

        ob_start();
        $this->subject->run([$filename]);
        $out = ob_get_clean();
        static::assertStringStartsWith(sprintf('Linting %1$d file(s)...%2$s%2$s', 1, PHP_EOL), $out);
        static::assertStringEndsWith(sprintf('%1$sOK%1$s', PHP_EOL), $out);
    }

    public function testRunWithSingleExistingButBrokenFile()
    {
        $filename = __DIR__ . '/../files/broken.html';

        ob_start();
        $this->subject->run([$filename]);
        $out = ob_get_clean();
        static::assertStringStartsWith(sprintf('Linting %1$d file(s)...%2$s%2$s', 1, PHP_EOL), $out);
        static::assertContains('The following errors have been recorded', $out);
        static::assertContains('Attribute my does not have value', $out);
    }

    public function testRunWithMultipleFiles()
    {
        $filename1 = __DIR__ . '/../files/ok.html';
        $filename2 = __DIR__ . '/../files/broken.html';

        ob_start();
        $this->subject->run([$filename1, $filename2]);
        $out = ob_get_clean();
        static::assertStringStartsWith(sprintf('Linting %1$d file(s)...%2$s%2$s', 2, PHP_EOL), $out);
        static::assertContains('The following errors have been recorded', $out);
        static::assertContains('Attribute my does not have value', $out);
    }


}
