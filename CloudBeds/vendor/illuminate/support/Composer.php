<?php

namespace Illuminate\Support;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class Composer
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The working path to regenerate from.
     *
     * @var string|null
     */
    protected $workingPath;

    /**
     * Create a new Composer manager instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @param  string|null $workingPath
     * @return void
     */
    public function __construct(Filesystem $files, $workingPath = null)
    {
        $this->files = $files;
        $this->workingPath = $workingPath;
    }

    /**
     * Regenerate the optimized Composer autoloader files.
     *
     * @return void
     */
    public function dumpOptimized()
    {
        $this->dumpAutoloads('--optimize');
    }

    /**
     * Regenerate the Composer autoloader files.
     *
     * @param  string|array $extra
     * @return void
     */
    public function dumpAutoloads($extra = '')
    {
        $extra = $extra ? (array)$extra : [];

        $command = array_merge($this->findComposer(), ['dump-autoload'], $extra);

        $this->getProcess($command)->run();
    }

    /**
     * Get the composer command for the environment.
     *
     * @return array
     */
    protected function findComposer()
    {
        if ($this->files->exists($this->workingPath . '/composer.phar')) {
            return [$this->phpBinary(), 'composer.phar'];
        }

        return ['composer'];
    }

    /**
     * Get the PHP binary.
     *
     * @return string
     */
    protected function phpBinary()
    {
        return ProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));
    }

    /**
     * Get a new Symfony process instance.
     *
     * @param  array $command
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess(array $command)
    {
        return (new Process($command, $this->workingPath))->setTimeout(null);
    }

    /**
     * Set the working path used by the class.
     *
     * @param  string $path
     * @return $this
     */
    public function setWorkingPath($path)
    {
        $this->workingPath = realpath($path);

        return $this;
    }
}