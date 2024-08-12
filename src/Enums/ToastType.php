<?php

declare(strict_types=1);

namespace Janmuran\LaravelInertiaCommandBus\Enums;

enum ToastType: string
{
    case SUCCESS = 'success';
    case WARNING = 'warning';
    case ERROR = 'error';
}
