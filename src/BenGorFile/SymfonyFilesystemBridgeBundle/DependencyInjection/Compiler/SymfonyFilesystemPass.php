<?php

/*
 * This file is part of the BenGorFile package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorFile\SymfonyFilesystemBridgeBundle\DependencyInjection\Compiler;

use BenGorFile\SymfonyFilesystemBridge\Infrastructure\Domain\Model\SymfonyFilesystem;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Register Symfony filesystem services compiler pass.
 *
 * Service declaration via PHP allows more
 * flexibility with customization extend files.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SymfonyFilesystemPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');
        foreach ($config['file_class'] as $key => $file) {
            if ('symfony' !== $file['storage']) {
                continue;
            }

            $container->setDefinition(
                'bengor.file.infrastructure.domain.model.symfony_filesystem_' . $key,
                new Definition(
                    SymfonyFilesystem::class, [
                        $file['upload_destination'],
                        new Reference('filesystem'),
                    ]
                )
            )->setPublic(false);

            $container->setAlias(
                'bengor_file.' . $key . '.filesystem',
                'bengor.file.infrastructure.domain.model.symfony_filesystem_' . $key
            );
        }
    }
}
