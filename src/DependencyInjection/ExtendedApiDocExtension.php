<?php

declare(strict_types=1);

namespace PBaszak\ExtendedApiDoc\DependencyInjection;

use PBaszak\ExtendedApiDoc\ExtendedApiDocBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ExtendedApiDocExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // do nothing
    }

    public function getAlias(): string
    {
        return ExtendedApiDocBundle::ALIAS;
    }

    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasParameter('pbaszak.extended_api_doc_bundle.dev_mode') && true === $container->getParameter('pbaszak.extended_api_doc_bundle.dev_mode')) {
            return;
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}
