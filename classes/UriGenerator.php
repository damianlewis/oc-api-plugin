<?php

declare(strict_types=1);

namespace DamianLewis\Api\Classes;

trait UriGenerator
{
    /**
     * @var string
     */
    protected $basePath;
    protected $resourceId = 'id';

    /**
     * @var bool
     */
    protected $useAbsolutePath = false;

    /**
     * Returns a URI for the given parameters.
     *
     * @param array $parameters
     * @return string
     */
    public function getUri(array $parameters = []): string
    {
        if ($this->useAbsolutePath) {
            return url($this->basePath ?: '', $parameters);
        } else {
            if ($this->basePath !== null) {
                array_unshift($parameters, $this->basePath);
            }

            return '/' . implode('/', $parameters);
        }
    }

    /**
     * Sets the base path used to generate the URI.
     *
     * @param string $path
     */
    public function setBasePath(string $path): void
    {
        $path = trim($path, '/');

        $this->basePath = $path;
    }

    /**
     * Sets the field used to identify the resource.
     *
     * @param string $field
     */
    public function setResourceId(string $field): void
    {
        $this->resourceId = $field;
    }

    /**
     * Sets whether the generated URI should be absolute or relative.
     *
     * @param bool $useAbsolute
     */
    public function useAbsolutePath(bool $useAbsolute = true): void
    {
        $this->useAbsolutePath = $useAbsolute;
    }
}
