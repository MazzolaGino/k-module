<?php

namespace KLib\Manager;

use KLib\Implementation\ControllerManagerInterface;
use KLib\Implementation\PathInterface;
use KLib\Implementation\ConfigurationInterface;

class ControllerManager implements ControllerManagerInterface
{
    public const ACTION_POSTFIX = '_action';

    /**
     *
     * @var array
     */
    private array $list = [];

    /**
     *
     * @var ConfigurationInterface
     */
    private ConfigurationInterface $config;

    /**
     *
     * @var PathInterface
     */
    private PathInterface $path;

    /**
     *
     * @param ConfigurationInterface $config
     * @param PathInterface $path
     * @param array $list
     */
    public function __construct(ConfigurationInterface $config, PathInterface $path, array $list)
    {
        $this->config = $config;
        $this->path = $path;

        foreach ($list as $controller) {
            if (isset($controller['class'])) {
                $this->list[$controller['class']] = [
                    'path' => $this->path->fromController($controller['class']),
                    'data' => $controller
                ];
            }
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \KLib\Implementation\ControllerManagerInterface::load()
     */
    public function load(): void
    {
        foreach ($this->list as $key => $controller) {
            $path = $controller['path'];

            if (file_exists($path)) {
                require_once $path;

                $controllerString = $this->config->get('controller_namespace').$key;
                $controllerClass = new $controllerString();
                $methods = $this->getActionMethods($controllerClass);

                foreach ($methods as $m) {
                    if ($this->isActionMethod($m)) {
                        $actionName = $this->getActionName($m, $controller);
                        $param = $this->buildActionParameters($controller, $m, $controllerClass, $actionName);
                        $this->registerAction($param);
                    }
                }
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param mixed $controllerClass
     * @return array
     */
    private function getActionMethods(mixed $controllerClass): array
    {
        return get_class_methods($controllerClass);
    }

    /**
     * Undocumented function
     *
     * @param string $method
     * @return boolean
     */
    private function isActionMethod(string $method): bool
    {
        return strpos($method, self::ACTION_POSTFIX) !== false;
    }

    /**
     * Undocumented function
     *
     * @param [type] $method
     * @param [type] $controller
     * @return string
     */
    private function getActionName($method, $controller): string
    {
        $actionName = str_replace(self::ACTION_POSTFIX, '', $method);
        if (isset($controller['data']['actions'][$actionName])) {
            $actionName = str_replace('_', ' ', $controller['data']['actions'][$actionName]);
        }
        return $actionName;
    }

    /**
     * Undocumented function
     *
     * @param [type] $controller
     * @param [type] $method
     * @param [type] $controllerClass
     * @param [type] $actionName
     * @return array
     */
    private function buildActionParameters($controller, $method, $controllerClass, $actionName): array
    {
        return [
            'method' => $method,
            'controller' => $controller['data']['name'],
            'plugin' => $this->config->get('plugin'),
            'controllerClass' => $controllerClass,
            'action_name' => $actionName
        ];
    }

    /**
     * Undocumented function
     *
     * @param array $params
     * @return void
     */
    private function registerAction(array $params): void
    {
        $this->register($params);
    }

    /**
     *
     * @param array $param
     */
    private function register(array $param): void
    {
        add_action('admin_menu', function () use ($param) {
            add_menu_page($param['action_name'], $param['action_name'], 'manage_options', $param['plugin'] . '-' . $param['controller'] . '-' . str_replace(self::ACTION_POSTFIX, '', $param['method']), [
                $param['controllerClass'],
                $param['method']
            ], 'dashicons-admin-generic', 99);
        });
    }

    /**
     *
     * {@inheritDoc}
     * @see \KLib\Implementation\ControllerManagerInterface::getList()
     */
    public function getList(): array
    {
        return $this->list;
    }
}
