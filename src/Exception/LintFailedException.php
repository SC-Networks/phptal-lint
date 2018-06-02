<?php

namespace Scn\PhpTalLint\Exception;

/**
 * LintFailedException.
 */
class LintFailedException extends PhpTalLintException
{
    /**
     * @param \Exception $e
     */
    public function __construct(\Exception $e)
    {
        $this->message = $e->getMessage();
        $this->code = $e->getCode();
        $this->line = $e->getLine();
        $this->file = $e->getFile();
    }
}
