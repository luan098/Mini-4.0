<?php

namespace Mini\core;

use PDO;

/**
 * Cria de forma centralizada a conexão com o banco de dados
 * * Esta classe utiliza o Design Pattern Singleton para evitar a criação de instancias desnecessárias com o banco
 */
class Database
{
    private static $instance = null;
    private $pdo;

    /** 
     * Cria as devidas conexões com o banco
     */
    private function __construct()
    {
        /* Cria conexão PDO com o banco do sistema */
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => (ENVIRONMENT != 'development' ? PDO::ERRMODE_EXCEPTION : PDO::ERRMODE_WARNING));
        $this->pdo = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
    }

    private function __clone()
    {
    }

    /**
     * Não instanciar a classe normalmente usando new
     * PHP 8 não me deixa privar o wake up então deixei comentado
     */
    public function __wakeup()
    {
    }

    /** Busca a instancia da classe, se não houver cria uma */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** Busca a conexão pdo do sistema */
    public function getPdo()
    {
        return $this->pdo;
    }
}
