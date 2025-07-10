<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

if (!function_exists('successResponse')) {
    /**
     * Returns a standardized JSON response with support for pagination
     *
     * @param mixed $data Data to return (supports pagination and resource collections)
     * @param string|null $message Response message
     * @param int $code HTTP status code
     * @return \Illuminate\Http\JsonResponse
     */
    function successResponse($data, $msg = null, $status_code = 200)
    {
        $returnData = [
            'status'    => true,
            'message'   => $msg,
        ];

        // Check if this is a Resource Collection with pagination
        if ($data instanceof AnonymousResourceCollection && property_exists($data, 'resource') && $data->resource instanceof LengthAwarePaginator) {

            // Get the paginator from the resource collection
            $paginator = $data->resource;

            // Extract data items
            $returnData['data'] = $data->collection;

            // Extract pagination metadata
            $returnData['paginate'] = [
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'last_page' => $paginator->lastPage(),
                'total_pages' => $paginator->lastPage(),
            ];
        }
        // Check if this is a direct paginator instance
        elseif ($data instanceof LengthAwarePaginator) {
            $returnData['data'] = $data->items();
            $returnData['paginate'] = [
                'total' => $data->total(),
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'last_page' => $data->lastPage(),
                'total_pages' => $data->lastPage(),
            ];
        }
        // Regular data (no pagination)
        else {
            $returnData['data'] = $data;
        }

        return response()->json($returnData, $status_code);
    }
}

if (!function_exists('errorResponse')) {
    /**
     * Return a standardized JSON error response.
     *
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function errorResponse($message, int $code = 500)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
        ], $code);
    }
}
