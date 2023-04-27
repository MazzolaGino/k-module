<?php

namespace KLib;

use KLib\Implementation\PathInterface;
use KLib\Implementation\AssetManagerInterface;
use KLib\Implementation\ControllerManagerInterface;
use KLib\Implementation\ProcessorManagerInterface;
use KLib\Implementation\ClassManagerInterface;
use KLib\Implementation\ConfigurationInterface;
use Twig\Environment;

/**
 */
class App
{
    /**
     *
     * @var string
     */
    private string $env;

    /**
     *
     * @var PathInterface
     */
    private PathInterface $pa;

    /**
     *
     * @var ConfigurationInterface
     */
    private ConfigurationInterface $cnf;

    /**
     * Scripts Manager
     *
     * @var AssetManagerInterface
     */
    private AssetManagerInterface $scm;

    /**
     * Assets Manager
     *
     * @var AssetManagerInterface
     */
    private AssetManagerInterface $asm;

    /**
     *
     * @var ControllerManagerInterface
     */
    private ControllerManagerInterface $ctm;

    /**
     *
     * @var ProcessorManagerInterface
     */
    private ProcessorManagerInterface $prm;

    /**
     *
     * @var ClassManagerInterface
     */
    private ClassManagerInterface $clm;

    private Environment $twig;

    /**
     * Summary of __construct
     *
     * @param string $env
     * @param PathInterface $pa
     * @param ConfigurationInterface $cnf
     */
    public function __construct(
        string $env,
        PathInterface $pa,
        ConfigurationInterface $cnf,
        AssetManagerInterface $scm,
        AssetManagerInterface $asm,
        ControllerManagerInterface $ctm,
        ProcessorManagerInterface $prm,
        ClassManagerInterface $clm,
        Environment $twig
    ) {
        $this->env = $env;
        $this->pa = $pa;
        $this->cnf = $cnf;
        $this->scm = $scm;
        $this->asm = $asm;
        $this->ctm = $ctm;
        $this->prm = $prm;
        $this->clm = $clm;
        $this->twig = $twig;
    }

    public function on(): void
    {
        $this->getClm()->load();
        $this->getCtm()->load();
        $this->getPrm()->load();
    }

    /**
     *
     * @return Environment
     */
    public function twig(): Environment
    {
        return $this->twig;
    }

    /**
     *
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     *
     * @return \KLib\Implementation\PathInterface
     */
    public function getPa()
    {
        return $this->pa;
    }

    /**
     *
     * @return \KLib\Implementation\ConfigurationInterface
     */
    public function getCnf()
    {
        return $this->cnf;
    }

    /**
     *
     * @return \KLib\Implementation\AssetManagerInterface
     */
    public function getScm()
    {
        return $this->scm;
    }

    /**
     *
     * @return \KLib\Implementation\AssetManagerInterface
     */
    public function getAsm()
    {
        return $this->asm;
    }

    /**
     *
     * @return \KLib\Implementation\ControllerManagerInterface
     */
    public function getCtm()
    {
        return $this->ctm;
    }

    /**
     * @return \KLib\Implementation\ProcessorManagerInterface
     */
    public function getPrm()
    {
        return $this->prm;
    }

    /**
     * @return \KLib\Implementation\ClassManagerInterface
     */
    public function getClm()
    {
        return $this->clm;
    }
}
