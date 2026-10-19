<?php

declare(strict_types=1);

namespace Movioza;

use Movioza\Shared\Infrastructure\DependencyInjection\CompilerPass\RegisterControllerArgumentAttributeHandlersPass;
use Movioza\Shared\Infrastructure\DependencyInjection\CompilerPass\RegisterControllerAttributeHandlersPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterControllerAttributeHandlersPass());
        $container->addCompilerPass(new RegisterControllerArgumentAttributeHandlersPass());
    }
}
