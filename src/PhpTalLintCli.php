<?php

namespace Scn\PhpTalLint;

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

/**
 * PhpTalLintCli.
 */
final class PhpTalLintCli extends CLI
{
    /**
     * @var TestRunnerInterface
     */
    private $testRunner;

    /**
     * @param TestRunnerInterface $testRunner
     */
    public function __construct(TestRunnerInterface $testRunner)
    {
        parent::__construct();

        $this->testRunner = $testRunner;
    }

    /**
     * @param Options $options
     * @throws \splitbrain\phpcli\Exception
     */
    protected function setup(Options $options)
    {
        $options->setHelp('PhpTalLint - linting tool for phptal templates');
        $options->registerOption('directory', 'Directory to scan for templates', 'd', 'directory');
        $options->registerOption('file', 'Path to a single file', 'f', 'filename');
    }

    /**
     * @param Options $options
     * @throws \splitbrain\phpcli\Exception
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

    /**
     * @param string $path
     */
    private function handleDirectory($path)
    {
        $iterator = new \RegexIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(realpath($path))
            ),
            '/^.+\.(html|xhtml|tpl)$/i',
            \RecursiveRegexIterator::GET_MATCH
        );

        $files = [];

        foreach ($iterator as $file) {
            $files[] = current($file);
        }

        $this->testRunner->run($files);
    }

    /**
     * @param string $filename
     */
    private function handleFile($filename)
    {
        $path = realpath($filename);

        $this->testRunner->run([$path]);
    }
}
