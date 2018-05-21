<?php
namespace Scn\PhpTalLint;

use PhpTal\PHPTAL;

/**
 * TestRunner
 */
final class TestRunner implements TestRunnerInterface {

	/**
	 * @var string[]
	 */
	private $errors = [];

    /**
     * @param string[] $files
     * @return void
     */
    public function run(array $files)
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
     * @param string $filename
     * @return void
     * 
     * @throws Exception\PhpTalLintException
     */
    private function testSingleFile($filename)
    {
        try {
            $phptal = new PHPTAL($filename);
            $phptal->setForceReparse(true);
            $phptal->prepare();
        }
        catch (\Exception $e) {
            throw new Exception\LintFailedException($e);
        }
    }

	/**
	 * @return void
	 */
	private function handleErrors()
	{
		if (count($this->errors) == 0) {
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