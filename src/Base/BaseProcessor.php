<?php

namespace KLib\Base;

use KLib\App;
use KLib\Implementation\ProcessorInterface;
use KLib\Manager\ScriptManager;

/**
 * Summary of BaseController
 */
abstract class BaseProcessor implements ProcessorInterface
{
    protected array $actions;

    protected ScriptManager $scripts;

    protected string $processor;

    abstract public function getApp(): App;
    abstract public function getProcessorName(): string;

    public function init(): void 
    {
        $this->processor = $this->getProcessorName();
        $this->scripts = $this->getApp()->getScm();
        $this->process();
    }

    /**
     *
     * @return string
     */
    private function process()
    {
        $className = get_class($this);
        $methods = get_class_methods($this);
        $reflector = new \ReflectionClass($className);

        foreach ($methods as $methodName) {

            if (strpos($methodName, '_processor') === false) {
                continue;
            }

            $method = $reflector->getMethod($methodName);
            $hookParameters = $this->buildActionArgs($method->getDocComment(), $methodName);

            try {
                add_action(...$hookParameters);
            } catch (\Exception $e) {
                throw new \Exception(sprintf('Error adding action for method %s: %s', $methodName, $e->getMessage()));
            }

            $this->actions[$hookParameters[0]] = $methodName;
        }
    }

    protected function render(string $template, array $params): void
    {
        $dir = dirname((new \ReflectionClass($this))->getFileName());
        $tpl = $this->getApp()->getPa()->fromTemplate($this->processor, $template);

        if (!file_exists($tpl)) {
            throw new \InvalidArgumentException("Unable to find template at path ($tpl)");
        }

        extract($params);

        include $tpl;
    }

    private function buildActionArgs($doc, $method): mixed
    {
        preg_match('/@hook ([^\s]+)/', $doc, $matches);

        preg_match('/@priority ([^\s]+)/', $doc, $priority);

        preg_match('/@args ([^\s]+)/', $doc, $args);

        $params = [$matches[1], [$this, $method]];

        if (isset($priority[1]) && isset($args[1])) {
            $params[] = $priority[1];
            $params[] = $args[1];
        }

        return $params;
    }

}
