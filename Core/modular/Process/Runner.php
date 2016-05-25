<?php

namespace Core\Modular\Process;

use Core\Modular\Contracts\RunableInterface;
use Core\Modular\Repository;

class Runner implements RunableInterface
{
    /**
     * The module instance.
     *
     * @var \Core\Modular\Repository
     */
    protected $module;

    /**
     * The constructor.
     *
     * @param \Core\Modular\Repository $module
     */
    public function __construct(Repository $module)
    {
        $this->module = $module;
    }

    /**
     * Run the given command.
     *
     * @param string $command
     */
    public function run($command)
    {
        passthru($command);
    }
}
