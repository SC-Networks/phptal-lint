<?php

namespace Scn\PhpTalLint\Exception;

use Exception;

class LintFailedException extends PhpTalLintException
{
    public function __construct(Exception $e)
    {
        $this->message = $e->getMessage();
        $this->code = $e->getCode();
        $this->line = $e->getLine();
        $this->file = $e->getFile();
    }
}
