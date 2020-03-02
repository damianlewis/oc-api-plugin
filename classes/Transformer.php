<?php

declare(strict_types=1);

namespace DamianLewis\Api\Classes;

use League\Fractal\TransformerAbstract;
use October\Rain\Database\Collection;
use System\Models\File;

abstract class Transformer extends TransformerAbstract
{
    /**
     * Transforms a file using the given transformer.
     *
     * @param  File|null  $file
     * @param  Transformer  $transformer
     * @return array|null
     */
    protected function transformFile(?File $file, Transformer $transformer): ?array
    {
        if ($file === null) {
            return $file;
        }

        return $transformer->transform($file);
    }

    /**
     * Transforms a collection of files using the given transformer.
     *
     * @param  Collection  $collection
     * @param  Transformer  $transformer
     * @return array
     */
    protected function transformFiles(Collection $collection, Transformer $transformer): array
    {
        return $collection->map([$transformer, 'transform'])->all();
    }
}