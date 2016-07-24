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

namespace spec\BenGorFile\SymfonyFilesystemBridgeBundle\DependencyInjection\Compiler;

use BenGorFile\SymfonyFilesystemBridgeBundle\DependencyInjection\Compiler\SymfonyFilesystemPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Spec file of SymfonyFilesystemPass class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SymfonyFilesystemPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SymfonyFilesystemPass::class);
    }

    function it_implements_compiler_pass_interface()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_does_not_process_because_the_filesystem_is_not_symfony(ContainerBuilder $container)
    {
        $container->getParameter('bengor_file.config')->shouldBeCalled()->willReturn([
            'file_class' => [
                'file' => [
                    'class'              => 'AppBundle\Entity\File',
                    'firewall'           => 'main',
                    'persistence'        => 'doctrine_orm',
                    'storage'            => 'gaufrette',
                    'upload_destination' => 'gaufrette-configured-filesystem',
                ],
            ],
        ]);

        $this->process($container);
    }

    function it_processes_symfony_filesystem(ContainerBuilder $container, Definition $definition)
    {
        $container->getParameter('bengor_file.config')->shouldBeCalled()->willReturn([
            'file_class' => [
                'file' => [
                    'class'              => 'AppBundle\Entity\File',
                    'firewall'           => 'main',
                    'persistence'        => 'doctrine_orm',
                    'storage'            => 'symfony',
                    'upload_destination' => '/symfony/filesystem/path',
                ],
            ],
        ]);

        $container->setDefinition(
            'bengor.file.infrastructure.domain.model.file_filesystem',
            Argument::type(Definition::class)
        )->shouldBeCalled()->willReturn($definition);

        $container->setAlias(
            'bengor_file.file.filesystem',
            'bengor.file.infrastructure.domain.model.file_filesystem'
        )->shouldBeCalled();

        $this->process($container);
    }
}
