<?php

namespace NamTran\LaravelMakeRepositoryService\Generators;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * Class RepositoryInterfaceGenerator
 * @package NamTran\LaravelMakeRepositoryService\Generators
 */
class RepositoryInterfaceGenerator extends Generator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'repository/interface';

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
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode(): string
    {
        return 'repository_interfaces';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath() . '/' . $this->getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '/' . $this->getName() . 'RepositoryInterface.php';
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
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements(): array
    {
        return array_merge(parent::getReplacements(), []);
    }
}
