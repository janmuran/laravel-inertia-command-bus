<?php

declare(strict_types=1);

namespace Janmuran\LaravelInertiaCommandBus\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Janmuran\LaravelCommandBus\CommandBusInterface;
use Janmuran\LaravelCommandBus\CommandStorageInterface;
use Janmuran\LaravelCommandBus\Model\FormCommand;
use Janmuran\LaravelCommandBus\Response\ResponseStorageInterface;
use JMS\Serializer\ArrayTransformerInterface;
use Throwable;

final class CommandFormController
{
    public function __construct(
        private readonly CommandBusInterface $bus,
        private readonly ArrayTransformerInterface $arrayTransformer,
        private readonly CommandStorageInterface $commandStorage,
        private readonly ResponseStorageInterface $responseStorage,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function formAction(Request $request)
    {
        /** @var string|null $command */
        $command = $request->input('command');
        if ($command === null) {
            return back()->withError('Neznama akcia, doplnte command');
        }

        /** @var class-string<\App\Service\Tools\Command\FormCommand> $commandName */
        $commandName = $this->commandStorage->getCommandClass($command);
        $requestClass = $commandName::getRequestType();
        if ($requestClass !== null) {
            $request = resolve($requestClass);
        }

        $command = $this->arrayTransformer->fromArray($request->all(), $commandName);
        try {
            $this->bus->dispatch($command);
        } catch (Throwable $exception) {
            return back()->withError($exception->getMessage());
        }

        if ($this->responseStorage->getResponse() === null) {
            return;
        }

        return $this->responseStorage->getResponse();
    }
}
