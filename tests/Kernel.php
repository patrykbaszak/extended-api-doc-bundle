<?php

namespace PBaszak\ExtendedApiDoc\Tests;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const BUNDLES = [
        \Symfony\Bundle\FrameworkBundle\FrameworkBundle::class,
    ];

    public function registerBundles(): iterable
    {
        foreach (self::BUNDLES as $bundle) {
            yield new $bundle();
        }
    }
}
