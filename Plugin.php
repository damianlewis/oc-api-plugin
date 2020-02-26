<?php namespace DamianLewis\Api;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Api',
            'description' => 'Provides the base functionality for creating an API for custom plugins',
            'author'      => 'Damian Lewis',
            'icon'        => 'icon-cogs'
        ];
    }
}
