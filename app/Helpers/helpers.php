<?php

if (! function_exists('api_response')) {
    /**
     * Return a standardised JSON API response.
     */
    function api_response(mixed $data = null, string $message = 'Success', int $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $status < 400,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }
}

if (! function_exists('is_published')) {
    /**
     * Check whether a page is publicly visible right now.
     */
    function is_published(\App\Models\Page $page): bool
    {
        return $page->status === 'published'
            && ($page->publish_date === null || $page->publish_date->lte(now()));
    }
}
