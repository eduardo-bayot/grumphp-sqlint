<?php

declare(strict_types=1);

namespace Edubayot\GrumPHP;

use GrumPHP\Extension\ExtensionInterface;
use Edubayot\GrumPHP\Task\Sqlint;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Extension implements ExtensionInterface
{
    public function load(ContainerBuilder $container): void
    {
        $container->register('task.sqlint', Sqlint::class)
            ->addArgument(new Reference('config'))
            ->addArgument(new Reference('process_builder'))
            ->addArgument(new Reference('formatter.raw_process'))
            ->addTag('grumphp.task', ['config' => 'sqlint']);
    }
}
