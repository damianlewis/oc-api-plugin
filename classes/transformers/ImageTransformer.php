<?php

declare(strict_types=1);

namespace DamianLewis\Api\Classes\Transformers;

use DamianLewis\Api\Classes\Transformer;
use DamianLewis\Api\Classes\TransformerInterface;
use Model;
use System\Models\File;

class ImageTransformer extends Transformer implements TransformerInterface
{
    /**
     * Transforms the given image to include only the file path and title attributes.
     *
     * @param  Model  $item
     * @return array
     */
    public function transform(Model $item): ?array
    {
        if (!$item instanceof File) {
            return null;
        }

        return [
            'path' => $item->getPath(),
            'title' => $item->title
        ];
    }
}
