<?php

namespace DamianLewis\Api\Classes;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Model;
use Response;

class ApiController extends Controller
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var Manager
     */
    protected $fractalManager;

    /**
     * @param  Manager  $manager
     */
    public function __construct(Manager $manager)
    {
        $this->middleware('api');

//        $manager->setSerializer(new JsonApiSerializer());
        $this->fractalManager = $manager;
    }

    /**
     * Returns a transformed collection of eloquent models in JSON format.
     *
     * @param  EloquentCollection  $collection
     * @param  Transformable  $transformer
     * @return JsonResponse
     */
    public function respondWithCollection(EloquentCollection $collection, Transformable $transformer): JsonResponse
    {
        $resource = new Collection($collection, $transformer);

        return $this->respond([
            $this->fractalManager->createData($resource)->toArray()
        ]);
    }

    /**
     * Returns a transformed collection of paginated models in JSON format.
     *
     * @param  LengthAwarePaginator  $paginator
     * @param  Transformable  $transformer
     * @return JsonResponse
     */
    public function respondWithPagination(LengthAwarePaginator $paginator, Transformable $transformer): JsonResponse
    {
        $resource = new Collection($paginator->getCollection(), $transformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->respond([
            $this->fractalManager->createData($resource)->toArray()
        ]);
    }

    /**
     * Returns a transformed model in JSON format.
     *
     * @param  Model  $item
     * @param  Transformable  $transformer
     * @return JsonResponse
     */
    public function respondWithItem(Model $item, Transformable $transformer): JsonResponse
    {
        $resource = new Item($item, $transformer);

        return $this->respond([
            $this->fractalManager->createData($resource)->toArray()
        ]);
    }

    /**
     * Returns a resource not found error.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    public function respondedNotFound(string $message = 'Not found'): JsonResponse
    {
        $this->setStatusCode(404);

        return $this->respondWithError($message);
    }

    /**
     * Returns an internal server error.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    public function respondInternalServerError(string $message = 'Internal server error'): JsonResponse
    {
        $this->setStatusCode(500);

        return $this->respondWithError($message);
    }

    /**
     * Sends a JSON response.
     *
     * @param  array  $data
     * @param  array  $headers
     * @param  int  $options
     * @return JsonResponse
     */
    protected function respond(array $data, array $headers = [], int $options = 0): JsonResponse
    {
        return Response::json($data, $this->getStatusCode(), $headers, $options);
    }

    /**
     * Sends a JSON error response.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function respondWithError(string $message): JsonResponse
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'code' => $this->getStatusCode()
            ]
        ]);
    }

    /**
     * Returns the status code.
     *
     * @return int
     */
    protected function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Sets the status code.
     *
     * @param  int  $statusCode
     * @return void
     */
    protected function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}