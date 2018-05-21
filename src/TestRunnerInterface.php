<?php
namespace Scn\PhpTalLint;

/**
 * TestRunnerInterface
 */
interface TestRunnerInterface {

    /**
     * @param string[] $files
     * @return void
     */
    public function run(array $files);
}