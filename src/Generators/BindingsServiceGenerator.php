<?php
namespace NamTran\LaravelMakeRepositoryService\Generators;

/**
 * Class BindingsServiceGenerator
 * @package NamTran\LaravelMakeRepositoryService\Generators
 */
class BindingsServiceGenerator extends Generator
{

    /**
     * The placeholder for service bindings
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
        // Add entity service binding to the service service provider
        $provider = \File::get($this->getPath());
        $serviceInterface = '\\' . $this->getServiceInterface() . "::class";
        $serviceEloquent = '\\' . $this->getServiceClass() . "::class";
        \File::put($this->getPath(), str_replace($this->bindPlaceholder, "\$this->app->bind({$serviceInterface}, $serviceEloquent);" . PHP_EOL . '        ' . $this->bindPlaceholder, $provider));
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
        return 'service_provider';
    }

    /**
     * Gets service interface name
     *
     * @return string
     */
    public function getServiceInterface()
    {
        $serviceGenerator = new ServiceInterfaceGenerator([
            'name' => $this->name,
        ]);

        $service = $serviceGenerator->getRootNamespace() . '\\' . $serviceGenerator->getName();

        return str_replace([
                "\\",
                '/'
            ], '\\', $service) . 'ServiceInterface';
    }

    /**
     * Gets service full class name
     *
     * @return string
     */
    public function getServiceClass()
    {
        $serviceGenerator = new ServiceEloquentGenerator([
            'name' => $this->name,
        ]);

        $service = $serviceGenerator->getRootNamespace() . '\\' . $serviceGenerator->getName();

        return str_replace([
                "\\",
                '/'
            ], '\\', $service) . 'Service';
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
            'interface' => $this->getServiceInterface(),
            'class' => $this->getServiceClass(),
            'placeholder' => $this->bindPlaceholder,
        ]);
    }
}
