<?php

namespace DamianLewis\Api\Classes;

use Model;

interface Transformable
{
    /**
     * Transform the attributes of the given model.
     *
     * @param  Model  $item
     * @return array
     */
    public function transform(Model $item): array;
}
