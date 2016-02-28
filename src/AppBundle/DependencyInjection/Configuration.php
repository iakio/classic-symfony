<?php

namespace AppBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root("app");
        $rootNode
            ->children()
                ->arrayNode('members')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->isRequired()->end()
                            ->scalarNode('part')->isRequired()->end()
                            ->scalarNode('joinedDate')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;
        return $treeBuilder;
    }
}
