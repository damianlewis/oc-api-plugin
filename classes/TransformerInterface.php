<?php

declare(strict_types=1);

namespace DamianLewis\Api\Classes;

use Model;

interface TransformerInterface
{
    /**
     * Transform the attributes of the given model.
     *
     * @param  Model  $item
     * @return null|array
     */
    public function transform(Model $item): ?array;
}
