<?php

namespace NamTran\LaravelMakeRepositoryService\Generators;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use NamTran\LaravelMakeRepositoryService\Exceptions\FileAlreadyExistsException;

abstract class Generator
{

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The array of options.
     *
     * @var array
     */
    protected $options;

    /**
     * The shortname of stub.
     *
     * @var string
     */
    protected $stub;

    /**
     * Create new instance of this class.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->filesystem = new Filesystem;
        $this->options = $options;
    }

    /**
     * Set the filesystem instance.
     *
     * @param Filesystem $filesystem
     *
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem): self
    {
        $this->filesystem = $filesystem;

        return $this;
    }


    /**
     * Get stub template for generated file.
     *
     * @return string
     */
    public function getStub(): string
    {
        $path = Config::get('repository.generator.stubsOverridePath', __DIR__);

        if(!file_exists($path . '/Stubs/' . $this->stub . '.stub')){
            $path = __DIR__;
        }

        return (new Stub($path . '/Stubs/' . $this->stub . '.stub', $this->getReplacements()))->render();
    }


    /**
     * Get template replacements.
     *
     * @return array
     */
    public function getReplacements(): array
    {
        return [
            'class'          => $this->getClass(),
            'namespace'      => $this->getNamespace(),
            'root_namespace' => $this->getRootNamespace()
        ];
    }


    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return App::basePath();
    }


    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getBasePath() . '/' . $this->getName() . '.php';
    }


    /**
     * Get name input.
     *
     * @return string
     */
    public function getName(): string
    {
        $name = $this->name;
        if (Str::contains($this->name, '\\')) {
            $name = str_replace('\\', '/', $this->name);
        }
        if (Str::contains($this->name, '/')) {
            $name = str_replace('/', '/', $this->name);
        }

        return Str::studly(str_replace(' ', '/', ucwords(str_replace('/', ' ', $name))));
    }


    /**
     * Get application namespace
     *
     * @return string
     */
    public function getAppNamespace(): string
    {
        return Container::getInstance()->getNamespace();
    }


    /**
     * Get class name.
     *
     * @return string
     */
    public function getClass(): string
    {
        return Str::studly(class_basename($this->getName()));
    }


    /**
     * Get paths of namespace.
     *
     * @return array
     */
    public function getSegments(): array
    {
        return explode('/', $this->getName());
    }


    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace(): string
    {
        return Config::get('repository.generator.rootNamespace', $this->getAppNamespace());
    }


    /**
     * Get class-specific output paths.
     *
     * @param $class
     *
     * @param bool $directoryPath
     * @return string
     */
    public function getConfigGeneratorClassPath($class, $directoryPath = false): string
    {
        switch ($class) {
            case ('repositories' === $class):
                $path = Config::get('repository.generator.paths.repositories', 'Repositories');
                break;
            case ('repository_interfaces' === $class):
                $path = Config::get('repository.generator.paths.repository_interfaces', 'Repositories');
                break;
            case ('services' === $class):
                $path = Config::get('repository.generator.paths.services', 'Services');
                break;
            case ('service_interfaces' === $class):
                $path = Config::get('repository.generator.paths.service_interfaces', 'Services');
                break;
            case ('repository_provider' === $class):
                $path = Config::get('repository.generator.paths.repository_provider', 'RepositoryServiceProvider');
                break;
            case ('service_provider' === $class):
                $path = Config::get('repository.generator.paths.service_provider', 'BootstrapServiceProvider');
                break;
            default:
                $path = '';
        }

        if ($directoryPath) {
            $path = str_replace('\\', '/', $path);
        } else {
            $path = str_replace('/', '\\', $path);
        }


        return $path;
    }


    abstract public function getPathConfigNode();


    /**
     * Get class namespace.
     *
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        $segments = $this->getSegments();
        array_pop($segments);
        $rootNamespace = $this->getRootNamespace();
        if ($rootNamespace === false) {
            return null;
        }

        return 'namespace ' . rtrim($rootNamespace . '\\' . implode('\\', $segments), '\\') . ';';
    }


    /**
     * Setup some hook.
     *
     * @return void
     */
    public function setUp(): void
    {
        //
    }


    /**
     * Run the generator.
     *
     * @return int
     * @throws FileAlreadyExistsException
     */
    public function run(): int
    {
        $this->setUp();
        if ($this->filesystem->exists($path = $this->getPath()) && !$this->force) {
            throw new FileAlreadyExistsException($path);
        }
        if (!$this->filesystem->isDirectory($dir = dirname($path))) {
            $this->filesystem->makeDirectory($dir, 0777, true, true);
        }

        return $this->filesystem->put($path, $this->getStub());
    }


    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }


    /**
     * Determinte whether the given key exist in options array.
     *
     * @param string $key
     * @return bool
     */
    public function hasOption(string $key): bool
    {
        return array_key_exists($key, $this->options);
    }


    /**
     * Get value from options by given key.
     *
     * @param string $key
     * @param string|null $default
     *
     * @return string
     */
    public function getOption(string $key, $default = null): string
    {
        if (!$this->hasOption($key)) {
            return $default;
        }

        return $this->options[$key] ?: $default;
    }


    /**
     * Helper method for "getOption".
     *
     * @param string $key
     * @param string|null $default
     *
     * @return string
     */
    public function option(string $key, $default = null): string
    {
        return $this->getOption($key, $default);
    }


    /**
     * Handle call to __get method.
     *
     * @param string $key
     *
     * @return string|mixed
     */
    public function __get(string $key)
    {
        if (property_exists($this, $key)) {
            return $this->{$key};
        }

        return $this->option($key);
    }
}