<?php

namespace NamTran\LaravelMakeRepositoryService\Generators;

/**
 * Class Stub
 * @package NamTran\LaravelMakeRepositoryService\Generators
 */
class Stub
{
    /**
     * The base path of stub file.
     *
     * @var null|string
     */
    protected static $basePath;
    /**
     * The stub path.
     *
     * @var string
     */
    protected $path;
    /**
     * The replacements array.
     *
     * @var array
     */
    protected $replaces;

    /**
     * The constructor.
     *
     * @param string|null $path
     * @param array $replaces
     */
    public function __construct(string $path = null, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }

    /**
     * Create new self instance.
     *
     * @param string $path
     * @param array $replaces
     *
     * @return self
     */
    public static function create(string $path, array $replaces = []): self
    {
        return new static($path, $replaces);
    }

    /**
     * Set base path.
     *
     * @param string $path
     *
     * @return void
     */
    public static function setBasePath(string $path): void
    {
        static::$basePath = $path;
    }

    /**
     * Set replacements array.
     *
     * @param  array $replaces
     *
     * @return $this
     */
    public function replace(array $replaces = []): self
    {
        $this->replaces = $replaces;

        return $this;
    }

    /**
     * Get replacements.
     *
     * @return array
     */
    public function getReplaces(): array
    {
        return $this->replaces;
    }

    /**
     * Handle magic method __toString.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Get stub contents.
     *
     * @return string
     */
    public function render(): string
    {
        return $this->getContents();
    }

    /**
     * Get stub contents.
     *
     * @return mixed|string
     */
    public function getContents()
    {
        $contents = file_get_contents($this->getPath());
        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return static::$basePath . $this->path;
    }

    /**
     * Set stub path.
     *
     * @param string $path
     *
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
