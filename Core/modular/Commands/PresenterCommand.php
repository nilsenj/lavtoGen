<?php

namespace Core\modular\Commands;

use Core\Modular\Generators\FileAlreadyExistException;
use Core\Modular\Generators\FileGenerator;
use Core\Modular\Traits\ModuleCommandTrait;
use Pingpong\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;

class PresenterCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'presenter';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-presenter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new presenter for the specified model and module.';

    public function fire()
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();
        $presenter = studly_case($this->argument('module'));
        try {
            $presenterPath = base_path() . '/' . $presenter . '/Presenters/' . studly_case($this->argument('presenter')) . 'Presenter.php';
            $transformerPath = base_path() . '/' . $presenter . '/Transformers/' . studly_case($this->argument('model')) . 'Transformer.php';

            if (!\File::exists($presenterPath)) {
                with(new FileGenerator($path, $contents))->generate();
                $this->info("Presenter Created : {$presenterPath}");
            }

            if (!\File::exists($transformerPath)) {
                $this->info('Transformer doesn\'t exist - ' . $transformerPath );

                if ($this->confirm('Would you like to create a Transformer? [y|N]')) {
                    $this->call("module:make-transformer", [
                        'transformer' => $this->argument('model'),
                        'module' => $this->argument('module'),
                        'model' => $this->argument('model'),
                    ]);
                }
            }
        } catch (FileAlreadyExistException $e) {

            $this->error("File : {$path} already exists.");
        }
    }

    /**
     * Get presenter name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $presenterPath = $this->laravel['modules']->config('paths.generator.presenter');

        return $path . $presenterPath . '/' . $this->getPresenterName() . '.php';
    }

    /**
     * @return Stub
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/presenter.stub', [
            'MODULENAME' => $module->getStudlyName(),
            'PRESENTERRNAME' => $this->getPresenterName(),
            'NAMESPACE' => $module->getStudlyName(),
            'CLASS_NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getClass(),
            'LOWER_NAME' => $module->getLowerName(),
            'MODULE' => $this->getModuleName(),
            'NAME' => $this->getModuleName(),
            'STUDLY_NAME' => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('presenter', InputArgument::REQUIRED, 'The name of the presenter class.'),
            array('module', InputArgument::REQUIRED, 'The name of module will be used.'),
            array('model', InputArgument::REQUIRED, 'The model of module will be used.'),
        );
    }

    /**
     * @return array|string
     */
    protected function getPresenterName()
    {
        $presenter = studly_case($this->argument('presenter'));

        if (!str_contains(strtolower($presenter), 'presenter')) {
            $presenter = $presenter . 'Presenter';
        }

        return $presenter;
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return $this->laravel['modules']->config('paths.generator.presenter');
    }
}