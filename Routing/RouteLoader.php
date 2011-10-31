<?php

/*
 * This file is part of the LyraAdminBundle package.
 *
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\AdminBundle\Routing;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

/**
 * Admin routes loader.
 */
class RouteLoader extends FileLoader
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        foreach ($this->options['models'] as $model => $options) {
            foreach ($options['actions'] as $action => $actionOpts) {
                $defaults = array_merge(array(
                    '_controller' => $options['controller'].':'.$action,
                    'lyra_admin_model' => $model,
                    'lyra_admin_action' => $action
                ), $actionOpts['route_defaults']);

                $route = new Route('/'.$this->options['route_pattern_prefix'].'/'.$options['route_pattern_prefix'].'/'.$actionOpts['route_pattern'], $defaults);
                $collection->add($options['route_prefix'].'_'.$action, $route);
            }
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'lyra_admin' == $type;
    }
}