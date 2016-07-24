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

namespace BenGorFile\SymfonyFilesystemBridgeBundle;

use BenGorFile\FileBundle\DependentBenGorFileBundle;
use BenGorFile\SymfonyFilesystemBridgeBundle\DependencyInjection\Compiler\SymfonyFilesystemPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Symfony filesystem bridge bundle kernel class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileSymfonyFilesystemBridgeBundle extends Bundle
{
    use DependentBenGorFileBundle;

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $this->checkDependencies(['BenGorFileBundle'], $container);

        $container->addCompilerPass(new SymfonyFilesystemPass(), PassConfig::TYPE_OPTIMIZE);
    }
}
