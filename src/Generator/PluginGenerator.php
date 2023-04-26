<?php

namespace KLib\Generator;

class PluginGenerator
{
    private string $name;
    private string $namespace;


    public function __construct(string $name)
    {
        $this->name = $name;
        $this->namespace = $this->formatNamespace();
    }

    private function formatNamespace()
    {

        $str = str_replace('-', ' ', $this->name);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);

        return $str;
    }

    /**
     *
     * @param string $rootDir
     * @return void
     */
    public function generatePlugin(string $rootDir)
    {
        $rootDir = $rootDir . '/../' . $this->name;

        $this->createDirectory($rootDir)
            ->createDirectory($rootDir . "/src")
            ->createDirectory($rootDir . "/src/assets")
            ->createDirectory($rootDir . "/src/assets/js")
            ->createDirectory($rootDir . "/src/assets/css")
            ->createDirectory($rootDir . "/src/config")
            ->createFile($rootDir . "/src/config/dev.json", $this->generateJsonFileContent())
            ->createDirectory($rootDir . "/src/Controller")
            ->createFile($rootDir . "/src/Controller/Controller.php", $this->generateControllerCode())
            ->createDirectory($rootDir . "/src/Lib")
            ->createDirectory($rootDir . "/src/Lib/Core")
            ->createDirectory($rootDir . "/src/logs")
            ->createFile($rootDir . "/src/logs/debug.log", "")
            ->createDirectory($rootDir . "/src/Processor")
            ->createFile($rootDir . "/src/Processor/Processor.php", $this->generateControllerCode())
            ->createDirectory($rootDir . "/src/template")
            ->createFile($rootDir . "/src/.env", $this->generateEnvFileContent());
    }

    private function generateControllerCode(): string
    {
        $code = <<<EOF
        <?php

        namespace KLibPlugin\Controller;

        use KLib\App;
        use KLib\AppBuilder;
        use KLib\Base\BaseController;

        /**
         * Summary of Controller
         */
        class Controller extends BaseController
        {
            /**
             * Summary of getApp
             * @return App
             */
            public function getApp(): App {
                \$dir = WP_PLUGIN_DIR . '/K-module-test/src/';
                \$url = WP_PLUGIN_URL . '/K-module-test/src/';

                return (new AppBuilder(\$dir, \$url))->getApp();
            }

            public function getControllerName(): string
            {
                \$class = get_class(\$this);
                \$namespace = \$this->getApp()->getCnf()->get('controller_namespace');
                \$name = str_replace(\$namespace, '', \$class);
                \$name = str_replace('Controller', '', \$name);

                return strtolower(\$name);
            }
        }
        EOF;

        return $code;
    }

    private function generateProcessorCode(): string
    {
        $code = <<<EOF
        <?php

        namespace KLibPlugin\Processor;

        use KLib\App;
        use KLib\AppBuilder;
        use KLib\Base\BaseProcessor;

        class Processor extends BaseProcessor
        {
            /**
             *
             * @return App
             */
            public function getApp(): App
            {
                \$dir = WP_PLUGIN_DIR . '/K-module-test/src/';
                \$url = WP_PLUGIN_URL . '/K-module-test/src/';

                return (new AppBuilder(\$dir, \$url))->getApp();
            }

            /**
             *
             * @return string
             */

            public function getProcessorName(): string
            {
                \$class = get_class($this);
                \$namespace = \$this->getApp()->getCnf()->get('processor_namespace');
                \$name = str_replace(\$namespace, '', \$class);
                \$name = str_replace('Processor', '', \$name);

                return strtolower(\$name);
            }
        }
        EOF;

        return $code;
    }


    private function generateJsonFileContent(): string
    {
        $data = [
            "plugin" => $this->name,
            "scripts" => [],
            "classes" => ["../vendor/autoload"],
            "processor_namespace" => $this->namespace . "\\Processor\\",
            "processors" => [],
            "controller_namespace" => $this->namespace . "\\Controller\\",
            "controllers" => []
        ];

        $content = json_encode($data, JSON_PRETTY_PRINT);

        return $content;
    }


    /**
     * Undocumented function
     *
     * @param string $dirPath
     * @return \KLib\Generator\PluginGenerator
     */
    private function createDirectory(string $dirPath): PluginGenerator
    {
        if (!file_exists($dirPath)) {
            mkdir($dirPath);
        }

        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $filePath
     * @return \KLib\Generator\PluginGenerator
     */
    private function createFile(string $filePath, string $content): PluginGenerator
    {
        if (!file_exists($filePath)) {
            file_put_contents($filePath, $content);
        }

        return $this;
    }

    private function generateEnvFileContent(): string
    {
        $content = "env=dev\n";
        $content .= "language=fr\n";

        return $content;
    }
}
