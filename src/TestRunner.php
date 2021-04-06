<?php

declare(strict_types=1);

namespace Scn\PhpTalLint;

use PhpTal\PHPTAL;
use Throwable;

final class TestRunner implements TestRunnerInterface
{
    /**
     * @var \Exception[]
     */
    private array $errors = [];

    /**
     * @param string[] $files
     *
     * @throws Exception\PhpTalLintException
     * @throws Throwable
     */
    public function run(array $files): void
    {
        printf(
            'Linting %1$d file(s)...%2$s%2$s',
            count($files),
            PHP_EOL
        );

        foreach ($files as $file) {
            try {
                $this->testSingleFile($file);

                echo '.';
            } catch (Exception\LintFailedException $e) {
                $this->errors[] = $e;
                echo 'F';
            }
        }

        $this->handleErrors();
    }

    /**
     * @throws Exception\PhpTalLintException
     * @throws Throwable
     */
    private function testSingleFile(string $filename): void
    {
        try {
            $phptal = new PHPTAL($filename);
            $phptal->allowPhpModifier();
            $phptal->setForceReparse(true);
            $phptal->prepare();
        } catch (\Exception $e) {
            throw new Exception\LintFailedException($e);
        }
    }

    private function handleErrors(): void
    {
        if (count($this->errors) === 0) {
            printf(
                '%1$s%1$sOK%1$s',
                PHP_EOL
            );

            return;
        }

        printf(
            '%1$s%1$sThe following errors have been recorded.%1$s',
            PHP_EOL
        );

        foreach ($this->errors as $error) {
            printf(
                '- %s in %s:%d%s',
                $error->getMessage(),
                $error->getFile(),
                $error->getLine(),
                PHP_EOL
            );
        }
    }
}
