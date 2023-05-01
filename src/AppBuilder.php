<?php

namespace KLib;

use KLib\App;
use KLib\Manager\Path;
use KLib\Manager\ConfigurationManager;
use KLib\Manager\ScriptManager;
use KLib\Manager\AssetManager;
use KLib\Manager\ControllerManager;
use KLib\Manager\ProcessManager;
use KLib\Manager\ClassesManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 *
 * @author kuroxray
 *
 */
class AppBuilder
{
    private App $app;

    /**
     *
     * @param string $path
     * @param string $assetsPath
     */
    public function __construct(string $path, string $assetsPath)
    {
        $env = $this->loadEnvironment($path);
        $pathObj = new Path($path, $assetsPath);
        $filename = $pathObj->fromEnv($env);
        $config = new ConfigurationManager($filename);

        $templateDir = $pathObj->path().'templates';
        $twig = new Environment(new FilesystemLoader([$templateDir]));
        

        $this->app = new App(
            $env,
            $pathObj,
            new ConfigurationManager($filename),
            new ScriptManager($pathObj, $config->has('scripts') ? $config->get('scripts') : []),
            new AssetManager($pathObj, $config->has('assets') ? $config->get('assets') : []),
            new ControllerManager($config, $pathObj, $config->has('controllers') ? $config->get('controllers') : []),
            new ProcessManager($config, $pathObj, $config->has('processors') ? $config->get('processors') : []),
            new ClassesManager($pathObj, $config->has('classes') ? $config->get('classes') : []),
            $twig
        );
    }

    /**
     *
     * @return App
     */
    public function getApp(): App
    {
        return $this->app;
    }


    /**
     *
     * @param string $path
     * @throws \Exception
     * @return string
     */
    private function loadEnvironment(string $path): string
    {

        $file = @fopen($path . '.env', 'r');

        if (!$file) {
            throw new \Exception("Unable to locate .env file {$path}.env.");
        }


        $env = '';

        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            if (strpos($line, 'env=') === 0) {
                $env = substr($line, 4);
                break;
            }
        }

        fclose($file);

        if (!$env) {
            throw new \Exception('Unable to determine environment type.');
        }

        return $env;
    }
}
