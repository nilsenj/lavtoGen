<?php

namespace Core\modular\Commands;


use Core\Modular\Generators\FileAlreadyExistException;
use Core\Modular\Generators\FileGenerator;
use Core\Modular\Traits\ModuleCommandTrait;
use Pingpong\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;

class RepositoryEloquentCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'repository';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new eloquent repository for the specified model and module.';

    /**
     * Get repository name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $repositoryPath = $this->laravel['modules']->config('paths.generator.repository');

        return $path . $repositoryPath . '/' . $this->getRepositoryEloquentName() . '.php';
    }

    /**
     * @return Stub
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/eloquent.stub', [
            'MODULENAME' => $module->getStudlyName(),
            'MODEL' => studly_case($this->argument('model')),
            'REPOSITORYNAME' => $this->getRepositoryName(),
            'REPOSITORYELOQUENTNAME' => $this->getRepositoryEloquentName(),
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
     * rewritten fire function
     */
    public function fire()
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();
        $repository = studly_case($this->argument('repository'));
        try {
            $repositoryIntPath = base_path() . '/'. $this->argument('module') . '/Interfaces/' . $repository . 'Repository.php';
            $eloquentRepositoryPath = base_path() . '/'. $this->argument('module') . '/Repositories/' . $repository . 'RepositoryEloquent.php';
            $modelPath = base_path() . '/'. $this->argument('module') . '/Entities/' . $repository . '.php';
            if (!\File::exists($eloquentRepositoryPath)) {

                with(new FileGenerator($path, $contents))->generate();
                $this->info("Repository Created : {$eloquentRepositoryPath}");
            }
            
            if (!\File::exists($repositoryIntPath)) {

                $this->call('module:make-repository_int', [
                    'repository' => $this->argument('model'),
                    'module' => $this->argument('module'),
                    'model' => $this->argument('model')
                ]);
            }
            if (!\File::exists($modelPath)) {

                $this->call('module:make-model', [
                    'model' => $this->argument('model'),
                    'module' => $this->argument('module')
                ]);
            }

        } catch (FileAlreadyExistException $e) {
            $this->error("File : {$path} already exists.");
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('repository', InputArgument::REQUIRED, 'The name of the repository class.'),
            array('module', InputArgument::REQUIRED, 'The name of module will be used.'),
            array('model', InputArgument::REQUIRED, 'The name of model in module will be used.'),
        );
    }

    /**
     * @return array|string
     */
    protected function getRepositoryName()
    {
        $repository = studly_case($this->argument('repository'));

        if (!str_contains(strtolower($repository), 'repository')) {
            $repository = $repository . 'Repository';
        }

        return $repository;
    }

    /**
     * @return array|string
     */
    protected function getRepositoryEloquentName()
    {
        $repository = studly_case($this->argument('repository'));

        if (!str_contains(strtolower($repository), 'repository')) {
            $repository = $repository . 'RepositoryEloquent';
        }

        return $repository;
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return $this->laravel['modules']->config('paths.generator.repository');
    }

}