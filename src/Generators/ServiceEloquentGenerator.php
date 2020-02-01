<?php

namespace NamTran\LaravelMakeRepositoryService\Generators;

/**
 * Class ServiceEloquentGenerator
 * @package NamTran\LaravelMakeRepositoryService\Generators
 */
class ServiceEloquentGenerator extends Generator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'service/eloquent';

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
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'services';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() . '/' . parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '/' . $this->getName() . 'Service.php';
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
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        $service = parent::getRootNamespace() . parent::getConfigGeneratorClassPath('service_interfaces') . '\\' . $this->name . 'ServiceInterface;';
        $service = str_replace([
            "\\",
            '/'
        ], '\\', $service);

        return array_merge(parent::getReplacements(), [
            'service'    => $service
        ]);
    }
}
