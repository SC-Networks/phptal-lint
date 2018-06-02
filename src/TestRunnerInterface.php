<?php

namespace Scn\PhpTalLint;

/**
 * TestRunnerInterface.
 */
interface TestRunnerInterface
{
    /**
     * @param string[] $files
     */
    public function run(array $files);
}
