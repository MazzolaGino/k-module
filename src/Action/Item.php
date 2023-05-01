<?php 

namespace KLib\Action;
use PHPStan\DependencyInjection\ParameterNotFoundException;


class Item {

    private array $action;
    private array $hook;
    private array $priority;
    private array $args;

    private array $method;

    public function __construct(array $hook, array $method, ?array $priority, ?array $args, ?array $action) {
        $this->action = $action;
        $this->hook = $hook;
        $this->priority = $priority;
        $this->args = $args;
        $this->method = $method;
    }

    public function executeAdd() {

        // Détermine l'action à ajouter
        $action = $this->action[1] ?? 'add_action';
    
        // Vérifie la présence de l'argument @hook
        if(!isset($this->hook[1])) {
            throw new \Exception("The comment @hook is missing in the definition of your processor in the method ({$this->method[1]})");
        }
    
        // Détermine les paramètres de l'action
       
            $h = $this->hook[1];
            $m = $this->method;
            $p = $this->priority[1] ?? null;
            $a = $this->args[1] ?? null;
        
            var_dump($this->method[1]);
    
        // Ajoute l'action
        $action($h, $m, $p, $a);
    }
}