<?php

namespace Scn\PhpTalLint;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Exception;
use splitbrain\phpcli\Options;

final class PhpTalLintCli extends CLI
{
    private TestRunnerInterface $testRunner;

    public function __construct(TestRunnerInterface $testRunner)
    {
        parent::__construct();

        $this->testRunner = $testRunner;
    }

    /**
     * @throws Exception
     */
    protected function setup(Options $options)
    {
        $options->setHelp('PhpTalLint - linting tool for phptal templates');
        $options->registerOption('directory', 'Directory to scan for templates', 'd', 'directory');
        $options->registerOption('file', 'Path to a single file', 'f', 'filename');
    }

    /**
     * @throws Exception
     */
    protected function main(Options $options)
    {
        if ($options->getOpt('directory')) {
            $this->handleDirectory($options->getOpt('directory'));
        } elseif ($options->getOpt('file')) {
            $this->handleFile($options->getOpt('file'));
        } else {
            echo $options->help();
        }
    }

    private function handleDirectory(string $path): void
    {
        $iterator = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(realpath($path))
            ),
            '/^.+\.(html|xhtml|tpl)$/i',
            RecursiveRegexIterator::GET_MATCH
        );

        $files = [];

        foreach ($iterator as $file) {
            $files[] = current($file);
        }

        $this->testRunner->run($files);
    }

    private function handleFile(string $filename): void
    {
        $path = realpath($filename);

        $this->testRunner->run([$path]);
    }
}
