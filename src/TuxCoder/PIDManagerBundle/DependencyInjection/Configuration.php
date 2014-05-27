<?php

namespace TuxCoder\PIDManagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pid_manager');
        
        $rootNode
          ->children()
            //@todo add config info and example
            // ->info('what my_type configures')
            // ->example('example setting')
            ->arrayNode('commands')
              ->prototype('array')
                ->children()
                  ->scalarNode('name')->isRequired()->end()
                  ->scalarNode('pid_path')->isRequired()->end()
                ->end()
              ->end()
            ->end()
          ->end();
        
        return $treeBuilder;
    }
}
