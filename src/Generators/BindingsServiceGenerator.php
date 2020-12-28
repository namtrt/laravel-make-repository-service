<?php
namespace NamTran\LaravelMakeRepositoryService\Generators;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

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

    public function run(): int
    {
        // Add entity service binding to the service service provider
        $provider = File::get($this->getPath());
        $serviceInterface = "\\" . $this->getServiceInterface() . "::class";
        $serviceEloquent = "\\" . $this->getServiceClass() . "::class";
        return File::put($this->getPath(), str_replace($this->bindPlaceholder, "\$this->app->bind({$serviceInterface}, $serviceEloquent);" . PHP_EOL . '        ' . $this->bindPlaceholder, $provider));
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
        return Config::get('repository.generator.basePath', App::path());
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'service_provider';
    }

    /**
     * Gets service interface name
     *
     * @return string
     */
    public function getServiceInterface(): string
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
    public function getServiceClass(): string
    {
        $serviceGenerator = new ServiceEloquentGenerator([
            'name' => $this->name,
        ]);

        $service = $serviceGenerator->getRootNamespace() . "\\" . $serviceGenerator->getName();

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
            'interface' => $this->getServiceInterface(),
            'class' => $this->getServiceClass(),
            'placeholder' => $this->bindPlaceholder,
        ]);
    }
}
