<?php

namespace NamTran\LaravelMakeRepositoryService\Generators;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * Class RepositoryEloquentGenerator
 * @package NamTran\LaravelMakeRepositoryService\Generators
 */
class RepositoryEloquentGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'repository/eloquent';

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
        return 'Repositories';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath() . '/' . $this->getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '/' . $this->getName() . 'Repository.php';
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
        $repository = parent::getRootNamespace() . $this->getConfigGeneratorClassPath('repository_interfaces') . '\\' . $this->name . 'RepositoryInterface;';
        $repository = str_replace([
            "\\",
            '/'
        ], '\\', $repository);

        return array_merge(parent::getReplacements(), [
            'repository'    => $repository
        ]);
    }
}
