<?php

namespace Core\Modular\Commands;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Pingpong\Support\Stub;
use Core\Modular\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Core\Modular\Generators\FileAlreadyExistException;
use Core\Modular\Generators\FileGenerator;

class ModelCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'model';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new model for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('model', InputArgument::REQUIRED, 'The name of model will be created.'),
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('fillable', null, InputOption::VALUE_OPTIONAL, 'The fillable attributes.', null),
        );
    }

    public function fire() {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();
        $model = studly_case($this->argument('model'));
        
        try {
            $modelPath = base_path() . '/'. $this->argument('module') . '/Entities/' . $model . '.php';
            $repositoryPath = base_path() . '/'. $this->argument('module') . '/Repositories/' . $model . 'RepositoryEloquent.php';
            $transformerPath = base_path() . '/'. $this->argument('module') . '/Transformers/' . $model . 'Transformer.php';
            $presenterPath = base_path() . '/'. $this->argument('module') . '/Presenters/' . $model . 'Presenter.php';
            
            if (!\File::exists($modelPath)) {

                with(new FileGenerator($path, $contents))->generate();

                $this->info("Created model: {$modelPath}");
            }
            if (!\File::exists($repositoryPath)) {

                $this->call('module:make-repository', [
                    'repository' => $this->argument('model'),
                    'module' => $this->argument('module'),
                    'model' => $this->argument('model'),
                ]);
            }

            if (!\File::exists($transformerPath)) {

                $this->call('module:make-transformer', [
                    'transformer' => $this->argument('model'),
                    'module' => $this->argument('module'),
                    'model' => $this->argument('model'),
                ]);
            }

            if (!\File::exists($presenterPath)) {

                if ($this->confirm('Would you like to create a Presenter? [y|N]')) {
                    $this->call('module:make-presenter', [
                        'presenter' => $this->argument('model'),
                        'module' => $this->argument('module'),
                        'model' => $this->argument('model'),
                    ]);
                }
            }

        } catch (FileAlreadyExistException $e) {
            $this->error("File : {$path} already exists.");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/model.stub', [
            'NAME'              => $this->getModelName(),
            'FILLABLE'          => $this->getFillable(),
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
            'CREATED'  => Carbon::now(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $seederPath = $this->laravel['modules']->config('paths.generator.model');

        return $path.$seederPath.'/'.$this->getModelName().'.php';
    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        return Str::studly($this->argument('model'));
    }

    /**
     * @return string
     */
    private function getFillable()
    {
        $fillable = $this->option('fillable');

        if (!is_null($fillable)) {
            $arrays = explode(',', $fillable);

            return json_encode($arrays);
        }

        return '[]';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return $this->laravel['modules']->config('paths.generator.model');
    }
}
