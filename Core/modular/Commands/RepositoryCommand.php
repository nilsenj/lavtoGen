<?php
/**
 * Created by PhpStorm.
 * User: nilse
 * Date: 5/30/2016
 * Time: 7:44 PM
 */

namespace Core\modular\Commands;

use Core\Modular\Generators\FileAlreadyExistException;
use Core\Modular\Generators\FileGenerator;
use Pingpong\Support\Stub;
use Core\Modular\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class RepositoryCommand extends GeneratorCommand
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
    protected $name = 'module:make-repository_int';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new repository interface for the specified model and module.';

    /**
     * Get repository name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $repositoryPath = $this->laravel['modules']->config('paths.generator.interfaces');

        return $path.$repositoryPath.'/'.$this->getRepositoryName().'.php';
    }
 
    /**
     * @return Stub
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/interface_repository.stub', [
            'MODULENAME'        => $module->getStudlyName(),
            'REPOSITORYNAME' => $this->getRepositoryName(),
            'NAMESPACE'         => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }

    public function fire()
    {

        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            $repositoryPath = base_path() . '/' . $this->argument('module') . '/Interfaces/' . $this->argument('repository') . 'Repository.php';

            if (!\File::exists($repositoryPath)) {

                with(new FileGenerator($path, $contents))->generate();

                $this->info("Created repository: {$repositoryPath}");
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
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
            array('model', InputArgument::OPTIONAL, 'The name of model in module will be used.'),
        );
    }

    /**
     * @return array|string
     */
    protected function getRepositoryName()
    {
        $repository = studly_case($this->argument('repository'));

        if (!str_contains(strtolower($repository), 'repository')) {
            $repository = $repository.'Repository';
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
        return $this->laravel['modules']->config('paths.generator.interfaces');
    }
}