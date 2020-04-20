<?php


namespace Sulu\Bundle\StylingBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SuluStylingBundle extends Bundle {
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container) {
        parent::build($container);
    }
}
