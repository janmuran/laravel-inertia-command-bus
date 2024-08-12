<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Janmuran\LaravelInertiaCommandBus\Enums\ToastType;

if (!function_exists('toast')) {
    function toast(ToastType $type, string $message, RedirectResponse|null $response = null): RedirectResponse|null
    {
        $toasts = session()->get('toasts', []);
        $toasts[] = [
            'id' => Str::uuid(),
            'type' => $type->value,
            'message' => $message,
        ];

        if ($response) {
            return $response->with('toasts', $toasts);
        }

        session()->flash('toasts', $toasts);

        return null;
    }
}


if (!function_exists('toastSuccess')) {
    function toastSuccess(string $message): RedirectResponse|null
    {
        return toast(ToastType::SUCCESS, $message);
    }
}

if (!function_exists('toastWarning')) {
    function toastWarning(string $message): RedirectResponse|null
    {
        return toast(ToastType::WARNING, $message);
    }
}

if (!function_exists('toastError')) {
    function toastError(string $message): RedirectResponse|null
    {
        return toast(ToastType::ERROR, $message);
    }
}
