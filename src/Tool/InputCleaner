<?php

namespace KLib\Tool;

class InputCleaner
{
    /**
     *
     * @var InputCleaner|null
     */
    private static ?InputCleaner $instance = null;

    private function __construct(){}

    public static function getInstance(): InputCleaner
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     *
     * @param string $input
     * @return string
     */
    public function cleanHtmlEntities(string $input): string
    {
        return htmlentities($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     *
     * @param string $input
     * @return string
     */
    public function decodeHtmlEntities(string $input): string
    {
        // Retourne la version originale de l'entrée avec toutes les entités HTML décodées
        return html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     *
     * @param string $input
     * @return string
     */
    public function cleanAddslashes(string $input): string
    {
        // Ajoute des antislashs avant les caractères spéciaux
        return addslashes($input);
    }

    /**
     *
     * @param string $input
     * @return string
     */
    public function removeAddslashes(string $input): string
    {
        // Retourne la version originale de l'entrée avec tous les antislashs supprimés
        return stripslashes($input);
    }

    /**
     *
     * @param string $input
     * @param \mysqli $mysqli
     * @return string
     */
    public function cleanForDatabase(string $input, \mysqli $mysqli): string
    {
        // Nettoie les entités HTML et ajoute des antislashs
        $cleaned = $this->cleanAddslashes($this->cleanHtmlEntities($input));

        // Échappe les caractères spéciaux pour éviter les injections SQL
        return $mysqli->real_escape_string($cleaned);
    }

    /**
     *
     * @param string $input
     * @param integer $filterType
     * @param string|null $regex
     * @return string
     */
    public function cleanInput(string $input, int $filterType, ?string $regex = null): string
    {
        // Nettoie l'entrée en utilisant le filtre fourni
        $options = [];

        if (!is_null($regex)) {
            $options['options'] = ['regexp' => $regex];
        }

        return filter_var($input, $filterType, $options);
    }

    /**
     * 
     *
     * @param string $input
     * @param string $regex
     * @return string
     */
    public function cleanCustomRegex(string $input, string $regex): string
    {
        return preg_replace($regex, '', $input);
    }
}
