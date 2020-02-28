<?php

declare(strict_types=1);

namespace DamianLewis\Api\Classes;

use League\Fractal\TransformerAbstract;
use System\Models\File;

abstract class Transformer extends TransformerAbstract
{
    protected function transformFile(?File $file, Transformer $transformer): ?array
    {
        if ($file === null) {
            return $file;
        }

        return $transformer->transform($file);
    }
}