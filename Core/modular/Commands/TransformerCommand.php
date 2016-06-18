<?php

namespace Core\modular\Commands;

use Core\Modular\Generators\FileGenerator;
use Pingpong\Support\Stub;
use Core\Modular\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;
use Core\Modular\Generators\FileAlreadyExistException;

class TransformerCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'transformer';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-transformer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new transformer for the specified model and module.';
    
    public function fire()
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();
        $transformer = studly_case($this->argument('transformer'));

        try {
            $transformerPath = base_path() . '/' . $this->argument('module') . '/Transformers/' . $transformer . 'Transformer.php';

            if (!\File::exists($transformerPath)) {

                with(new FileGenerator($path, $contents))->generate();

                $this->info("Created transformer: {$transformerPath}");
            }

        } catch (FileAlreadyExistException $e) {

            $this->error("File : {$path} already exists.");
        }

    }

    /**
     * Get transformer name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $transformerPath = $this->laravel['modules']->config('paths.generator.transformer');

        return $path.$transformerPath.'/'.$this->getTransformerName().'.php';
    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        return Str::studly($this->argument('model'));
    }
    /**
     * @return Stub
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/transformer.stub', [
            'MODULENAME'        => $module->getStudlyName(),
            'MODEL'              => $this->getModelName(),
            'TRANSFORMERRNAME'    => $this->getTransformerName(),
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

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('transformer', InputArgument::REQUIRED, 'The name of the transformer class.'),
            array('module', InputArgument::REQUIRED, 'The name of module will be used.'),
            array('model', InputArgument::REQUIRED, 'The name of model will be used.'),
        );
    }

    /**
     * @return array|string
     */
    protected function getTransformerName()
    {
        $transformer = studly_case($this->argument('transformer'));

        if (!str_contains(strtolower($transformer), 'transformer')) {
            $transformer = $transformer.'Transformer';
        }

        return $transformer;
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return $this->laravel['modules']->config('paths.generator.transformer');
    }
}