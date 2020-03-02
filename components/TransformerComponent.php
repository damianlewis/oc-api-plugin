<?php

declare(strict_types=1);

namespace DamianLewis\Api\Components;

use Cms\Classes\ComponentBase;
use DamianLewis\Api\Classes\Transformer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use Model;
use October\Rain\Database\Collection as OctoberCollection;

abstract class TransformerComponent extends ComponentBase
{
    /**
     * @var Manager
     */
    private $fractalManager;

    public function init(): void
    {
        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $this->fractalManager = $manager;
    }

    /**
     * Returns the transformed model.
     *
     * @param  Model  $item
     * @param  Transformer  $transformer
     * @return array
     */
    protected function transformItem(Model $item, Transformer $transformer): array
    {
        $resource = new Item($item, $transformer);

        return $this->fractalManager->createData($resource)->toArray();
    }

    /**
     * Returns the transformed collection.
     *
     * @param  OctoberCollection  $collection
     * @param  Transformer  $transformer
     * @return array
     */
    protected function transformCollection(OctoberCollection $collection, Transformer $transformer): array
    {
        $resource = new Collection($collection, $transformer);

        return $this->fractalManager->createData($resource)->toArray();
    }

    /**
     * Returns the transformed collection of paginated models.
     *
     * @param  LengthAwarePaginator  $paginator
     * @param  Transformer  $transformer
     * @return array
     */
    protected function transformPagination(LengthAwarePaginator $paginator, Transformer $transformer): array
    {
        $resource = new Collection($paginator->getCollection(), $transformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->fractalManager->createData($resource)->toArray();
    }
}