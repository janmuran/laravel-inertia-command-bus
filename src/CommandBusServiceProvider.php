<?php

declare(strict_types=1);

namespace Janmuran\LaravelInertiaCommandBus;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Janmuran\LaravelInertiaCommandBus\Enums\ToastType;
use Janmuran\LaravelInertiaCommandBus\Http\Controllers\CommandFormController;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    public function register()
    {
        RedirectResponse::macro(
            'withSuccess',
            fn(string $message) => toast(ToastType::SUCCESS, $message, $this)
        );

        RedirectResponse::macro(
            'withError',
            fn(string $message) => toast(ToastType::ERROR, $message, $this)
        );
    }

    public function boot(): void
    {
        $this->registerControllers();
    }

    private function registerControllers(): void
    {
        // @phpstan-ignore-next-line
        Route::post("command/form/run", '\\' . CommandFormController::class)
            ->name('commmand-bus-form-run-command')->middleware('web'); // @phpstan-ignore-line
    }
}
