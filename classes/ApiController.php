<?php

namespace DamianLewis\Api\Classes;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Model;
use October\Rain\Database\Collection as OctoberCollection;
use Response;

class ApiController extends Controller
{
    /**
     * @var int
     */
    private $statusCode = 200;

    /**
     * @var Manager
     */
    private $fractalManager;

    /**
     * @param  Manager  $manager
     */
    public function __construct(Manager $manager)
    {
        $this->middleware('api');

//        $manager->setSerializer(new JsonApiSerializer());
        $this->fractalManager = $manager;

        if (isset($_GET['include'])) {
            $this->fractalManager->parseIncludes($_GET['include']);
        }
    }

    /**
     * Returns a transformed collection of models in JSON format.
     *
     * @param  OctoberCollection  $collection
     * @return JsonResponse
     */
    public function respondWithCollection(OctoberCollection $collection, Transformer $transformer): JsonResponse
    {
        $resource = new Collection($collection, $transformer);

        return $this->respond($this->fractalManager->createData($resource)->toArray());
    }

    /**
     * Returns a transformed collection of paginated models in JSON format.
     *
     * @param  LengthAwarePaginator  $paginator
     * @param  TransformerInterface  $transformer
     * @return JsonResponse
     */
    public function respondWithPagination(LengthAwarePaginator $paginator, TransformerInterface $transformer): JsonResponse
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
     * @param  TransformerInterface  $transformer
     * @return JsonResponse
     */
    public function respondWithItem(Model $item, TransformerInterface $transformer): JsonResponse
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
    private function respond(array $data, array $headers = [], int $options = 0): JsonResponse
    {
        return Response::json($data, $this->getStatusCode(), $headers, $options);
    }

    /**
     * Sends a JSON error response.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    private function respondWithError(string $message): JsonResponse
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
    private function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Sets the status code.
     *
     * @param  int  $statusCode
     * @return void
     */
    private function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
