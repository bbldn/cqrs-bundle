<?php

namespace BBLDN\CQRS\CommandBus;

use LogicException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface as Container;
use Symfony\Component\HttpKernel\KernelInterface as Kernel;

class CommandBusImpl implements CommandBus
{
    private Container $container;

    private CommandRegistry $commandRegistry;

    /**
     * @param Kernel $kernel
     * @param CommandRegistry $commandRegistry
     */
    public function __construct(
        Kernel $kernel,
        CommandRegistry $commandRegistry
    )
    {
        $this->commandRegistry = $commandRegistry;
        $this->container = $kernel->getContainer();
    }

    /**
     * @param object $command
     * @return mixed
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function execute(object $command)
    {
        $commandClassName = get_class($command);
        if (false === $this->commandRegistry->has($commandClassName)) {
            throw new LogicException("Handler for $commandClassName not found");
        }

        /** @var string $commandHandlerClassName */
        $commandHandlerClassName = $this->commandRegistry->get($commandClassName);
        if (false === $this->container->has($commandHandlerClassName)) {
            throw new LogicException("Handler for $commandClassName not found");
        }

        /** @var callable $commandHandler */
        $commandHandler = $this->container->get($commandHandlerClassName);

        return call_user_func($commandHandler, $command);
    }
}