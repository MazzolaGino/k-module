<?php

namespace KLib\Tool;


class MysqlUpdater
{
    private $dirPath;
    private $pdo;

    private $dbconfig;

    public function __construct($dirPath, $dbconfig)
    {
        $this->dbconfig = $dbconfig;
        $this->dirPath = $dirPath;
        $this->pdo = $this->createPDO();
        
        $this->createTableVersion();

    }

    public function executeUpdates()
    {
        $version = $this->getCurrentVersion();
        $files = $this->getUpdateFiles();

        sort($files); // Tri des fichiers par ordre alphabétique (et donc version)

        foreach ($files as $file) {
            $fileVersion = $this->getVersionFromFileName($file);
            if ($fileVersion > $version) {
                $this->executeUpdate($file);
                $this->setCurrentVersion($fileVersion);
            }
        }

        echo 'Mise à jour effectuée, vous êtes maintenant en ' . $fileVersion;
    }

    private function getCurrentVersion()
    {
        return $this->getVersionOrCreate();
    }

    private function setCurrentVersion($version)
    {
        $query = "UPDATE version_table SET version = '$version' WHERE id = 1";
        $this->pdo->exec($query);
    }

    private function getUpdateFiles()
    {
        $files = scandir($this->dirPath);
        $updateFiles = [];
    
        foreach ($files as $file) {
            if (strpos($file, 'upd-ver') !== false) {
                $updateFiles[] = $file;
            }
        }
    
        return $updateFiles;
    }
    
    private function getVersionFromFileName($fileName)
    {
        $version = str_replace('upd-ver-', '', $fileName);
        return str_replace('.sql', '', $version);   
    }
    

    private function executeUpdate($fileName)
    {
        $query = \file_get_contents($this->dirPath . '/' . $fileName);
        $this->pdo->exec($query);
    }

    private function createPDO()
    {
        global $wpdb;

  

        $dsn = 'mysql:host=' . $this->dbconfig->dbhost . ';dbname=' . $this->dbconfig->dbname . ';charset=utf8';
        $pdo = new \PDO($dsn, $this->dbconfig->dbuser, $this->dbconfig->dbpassword);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
     
        return $pdo;
    }

    /**
     * Summary of createTableVersion
     * @return void
     */
    public function createTableVersion()
    {
 
        $this->pdo->exec(
            <<<EOF
            CREATE TABLE IF NOT EXISTS version_table (
                id INT(11) NOT NULL AUTO_INCREMENT,
                version VARCHAR(10) NOT NULL,
                PRIMARY KEY (id)
              );
            EOF
        );
        
    }

    private function columnExists($column, $table) {
        // Vérifie si une colonne existe dans une table de la base de données
        $sql = "SHOW COLUMNS FROM $table LIKE :column";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['column' => $column]);
        return $stmt->rowCount() > 0;
      }
      
      private function getVersion() {
        // Récupère la version actuelle dans la table `version_table`
        $sql = "SELECT version FROM version_table";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        return $result ? $result['version'] : null;
      }
      
      private function createInitialVersion() {
        // Insère la version initiale dans la table `version_table`
        $sql = "INSERT INTO version_table (version) VALUES ('0.0.0')";
        $stmt = $this->pdo->query($sql);
      }
      
      public function getVersionOrCreate() {
        // Récupère la version actuelle ou crée une version initiale si aucune n'existe
        if (!$this->columnExists('version', 'version_table')) {
          throw new \Exception('La table `version_table` n\'existe pas ou ne contient pas la colonne `version`.');
        }
        
        $version = $this->getVersion();
        
        if ($version === null) {
          $this->createInitialVersion();
          $version = '0.0.0';
        }
        
        return $version;
      }
      

    private function tableExists($table) 
    {
        // Vérifie si une table existe dans la base de données
        $sql = "SELECT 1 FROM $table LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        return $stmt !== false && $stmt->execute() !== false;
      }
}
