<?php
namespace NamTran\LaravelMakeRepositoryService\Generators;

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

    public function run()
    {
        // Add entity repository binding to the repository service provider
        $provider = \File::get($this->getPath());
        $repositoryInterface = '\\' . $this->getRepositoryInterface() . "::class";
        $repositoryEloquent = '\\' . $this->getRepositoryClass() . "::class";
        \File::put($this->getPath(), str_replace($this->bindPlaceholder, "\$this->app->bind({$repositoryInterface}, $repositoryEloquent);" . PHP_EOL . '        ' . $this->bindPlaceholder, $provider));
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() . '/Providers/' . parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return config('repository.generator.basePath', app()->path());
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'repository_provider';
    }

    /**
     * Gets repository interface name
     *
     * @return string
     */
    public function getRepositoryInterface()
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
    public function getRepositoryClass()
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
    public function getRootNamespace()
    {
        return parent::getRootNamespace() . parent::getConfigGeneratorClassPath($this->getPathConfigNode());
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {

        return array_merge(parent::getReplacements(), [
            'interface' => $this->getRepositoryInterface(),
            'class' => $this->getRepositoryClass(),
            'placeholder' => $this->bindPlaceholder,
        ]);
    }
}
