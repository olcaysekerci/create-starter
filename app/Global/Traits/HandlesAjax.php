<?php

namespace App\Global\Traits;

use Illuminate\Http\JsonResponse;

trait HandlesAjax
{
    /**
     * Ajax başarı cevabı döndür
     */
    protected function ajaxSuccess(string $message = 'İşlem başarılı', array $data = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Ajax hata cevabı döndür
     */
    protected function ajaxError(string $message = 'Bir hata oluştu', array $errors = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }

    /**
     * İsteğin Ajax olup olmadığını kontrol et
     */
    protected function isAjax(): bool
    {
        return request()->ajax() || request()->wantsJson();
    }
} 