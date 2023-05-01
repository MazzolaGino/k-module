<?php

namespace KLib\Base;

use KLib\App;
use KLib\Implementation\ControllerInterface;

/**
 * Summary of BaseController
 */
abstract class BaseController implements ControllerInterface
{
    private array $get;
    private array $post;
    private array $files;

    /**
     * Summary of getApp
     * @return App
     */
    abstract public function getApp(): App;
    abstract public function getControllerName(): string;


    /**
     * Summary of get
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        $this->get = $this->clean($_GET);
        return isset($this->get[$key]) ? $this->get[$key] : $default;
    }

    /**
     * Summary of post
     * @param string $key
     * @return mixed
     */
    public function post(string $key = ''): mixed
    {
        $this->post = $this->clean($_POST);

        if ($key === '') {
            return $this->post;
        }

        return isset($this->post[$key]) ? $this->post[$key] : null;
    }

    /**
     * Summary of file
     * @param string $key
     * @return mixed
     */
    public function file(string $key): mixed
    {
        $this->files = $this->clean($_FILES);
        return isset($this->files[$key]) ? $this->files[$key] : null;
    }

    /**
     * Summary of clean
     * @param mixed $data
     * @return mixed
     */
    private function clean(mixed $data): mixed
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);
                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }

        return $data;
    }
    /**
     * Summary of render
     * @param string $template
     * @param array $params
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function render(string $template, array $params): void
    {
        $app =  $this->getApp();
        echo $app->twig()->render($this->getControllerName() . '/' . $template.'.html.twig', $params);
    }

    /**
     * Summary of updateOption
     *
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     */
    protected function updateOption(string $k): mixed
    {
        $v = null;
        $toSave = !empty($this->post());

        if ($toSave && isset($this->post()[$k]) && $this->post()[$k] !== get_option($k)) {

            update_option($k, $this->post()[$k]);
            $v = $this->post()[$k];

        } else {
            $v = get_option($k);
        }

        return $v;
    }


    /**
     * Summary of save
     *
     * @param array $structure
     * @return array
     */
    protected function save(array $structure): array
    {
        $r = [];

        foreach ($structure as $v) {
            $r[$v] = $this->updateOption($v);
        }

        return $r;
    }
}
