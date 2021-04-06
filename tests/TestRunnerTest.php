<?php

declare(strict_types=1);

namespace Scn\PhpTalLint;

use PHPUnit\Framework\TestCase;

class TestRunnerTest extends TestCase
{
    private TestRunner $subject;

    public function setUp(): void
    {
        $this->subject = new TestRunner();
    }

    public function testRunWithEmptyFileset(): void
    {
        ob_start();
        $this->subject->run([]);
        $out = ob_get_clean();
        static::assertStringStartsWith(sprintf('Linting %1$d file(s)...%2$s%2$s', 0, PHP_EOL), $out);
        static::assertStringEndsWith(sprintf('%1$sOK%1$s', PHP_EOL), $out);
    }

    public function testRunWithSingleNonExistingFile(): void
    {
        $filename = '/some/random/file/which/should/exist/nowhere';

        ob_start();
        $this->subject->run([$filename]);
        $out = ob_get_clean();
        static::assertStringStartsWith(sprintf('Linting %1$d file(s)...%2$s%2$s', 1, PHP_EOL), $out);
        static::assertStringContainsString('The following errors have been recorded', $out);
        static::assertStringContainsString(sprintf('%1$s', $filename), $out);
    }

    public function testRunWithSingleExistingFile(): void
    {
        $filename = __DIR__.'/files/ok.html';

        ob_start();
        $this->subject->run([$filename]);
        $out = ob_get_clean();
        static::assertStringStartsWith(sprintf('Linting %1$d file(s)...%2$s%2$s', 1, PHP_EOL), $out);
        static::assertStringEndsWith(sprintf('%1$sOK%1$s', PHP_EOL), $out);
    }

    public function testRunWithSingleExistingButBrokenFile(): void
    {
        $filename = __DIR__.'/files/broken.html';

        ob_start();
        $this->subject->run([$filename]);
        $out = ob_get_clean();
        static::assertStringStartsWith(sprintf('Linting %1$d file(s)...%2$s%2$s', 1, PHP_EOL), $out);
        static::assertStringContainsString('The following errors have been recorded', $out);
        static::assertStringContainsString('Attribute my does not have value', $out);
    }

    public function testRunWithMultipleFiles(): void
    {
        $filename1 = __DIR__.'/files/ok.html';
        $filename2 = __DIR__.'/files/broken.html';

        ob_start();
        $this->subject->run([$filename1, $filename2]);
        $out = ob_get_clean();
        static::assertStringStartsWith(sprintf('Linting %1$d file(s)...%2$s%2$s', 2, PHP_EOL), $out);
        static::assertStringContainsString('The following errors have been recorded', $out);
        static::assertStringContainsString('Attribute my does not have value', $out);
    }
}
