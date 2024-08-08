<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

if (!function_exists('toast')) {
    function toast(string $type, string $message, RedirectResponse|null $response = null): RedirectResponse|null
    {
        $toasts = session()->get('toasts', []);
        $toasts[] = [
            'id' => Str::uuid(),
            'type' => $type,
            'message' => $message,
        ];

        if ($response) {
            return $response->with('toasts', $toasts);
        }

        session()->flash('toasts', $toasts);

        return null;
    }
}
