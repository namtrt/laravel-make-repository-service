<?php

namespace NamTran\LaravelMakeRepositoryService\Generators;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

/**
 * Class BindingsRepositoryGenerator
 * @package NamTran\LaravelMakeRepositoryService\Generators
 */
class BindingsRepositoryGenerator extends Generator
{

    /**
     * The placeholder for repository bindings
     *
     * @var string
     */
    public $bindPlaceholder = '//:end-bindings:';
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'bindings/bindings';

    /**
     * Run class
     */
    public function run(): int
    {
        // Add entity repository binding to the repository service provider
        $provider = File::get($this->getPath());
        $repositoryInterface = '\\' . $this->getRepositoryInterface() . "::class";
        $repositoryEloquent = '\\' . $this->getRepositoryClass() . "::class";
        return File::put($this->getPath(), str_replace($this->bindPlaceholder, "\$this->app->bind({$repositoryInterface}, $repositoryEloquent);" . PHP_EOL . '        ' . $this->bindPlaceholder, $provider));
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath() . '/Providers/' . $this->getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return Config::get('repository.generator.basePath', App::basePath());
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'repository_provider';
    }

    /**
     * Gets repository interface name
     *
     * @return string
     */
    public function getRepositoryInterface(): string
    {
        $repositoryGenerator = new RepositoryInterfaceGenerator([
            'name' => $this->name,
        ]);

        $repository = $repositoryGenerator->getRootNamespace() . '\\' . $repositoryGenerator->getName();

        return str_replace([
                "\\",
                '/'
            ], '\\', $repository) . 'RepositoryInterface';
    }

    /**
     * Gets repository full class name
     *
     * @return string
     */
    public function getRepositoryClass(): string
    {
        $repositoryGenerator = new RepositoryEloquentGenerator([
            'name' => $this->name,
        ]);

        $repository = $repositoryGenerator->getRootNamespace() . '\\' . $repositoryGenerator->getName();

        return str_replace([
                "\\",
                '/'
            ], '\\', $repository) . 'Repository';
    }

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace(): string
    {
        return parent::getRootNamespace() . $this->getConfigGeneratorClassPath($this->getPathConfigNode());
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements(): array
    {

        return array_merge(parent::getReplacements(), [
            'interface' => $this->getRepositoryInterface(),
            'class' => $this->getRepositoryClass(),
            'placeholder' => $this->bindPlaceholder,
        ]);
    }
}
