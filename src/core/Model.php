<?php

namespace Mini\core;

use InvalidArgumentException;
use JsonSerializable;
use PDO;
use PDOStatement;

/**
 * Model abstrata para ser herdada por todas as demais models
 * * Serve para herdar os comportamentos padrões de model do sistema como os getters, updates e delete
 */
abstract class Model
{
    /**
     * @var PDO Objeto PDO com a conexão ao banco já aplicada
     */
    protected $db;

    /**
     * @var string tabela do banco de dados ao qual a model representa
     */
    protected $table;

    public function __construct(string $table)
    {
        try {
            $this->table = $table;

            $this->db = Database::getInstance()->getPdo();
        } catch (\PDOException $e) {
            // if (ENVIRONMENT != 'production') echo $e->getMessage();
            exit('Não foi possível conectar com o banco de dados, contacte seu administrador.');
        }
    }

    public function __get($name)
    {
        if (in_array($name, ['table'])) {
            return $this->$name;
        }
        return null;
    }

    /** Realiza a consulta do statement passado e faz a buscar da quantidade de linhas total da mesma consulta ignorando o limit
     * ! AVISO: PRECISA INFORMAR "SQL_CALC_FOUND_ROWS" DENTRO DA CONSULTA APÓS O "SELECT" SEM USAR VIRGULA SOMENTE "SELECT SQL_CALC_FOUND_ROWS (restante da consulta)"
     * @param PDOStatement $stmt statement retornado após dar um execute na consulta
     * @param int $page pagina que esta sendo buscada
     * @param int $qtdPerPage quantidade de registros buscados por pagina
     * @param int $offset offset da consulta, em qual índice a consulta vai começar
     * 
     * @return ModelPaginatedReturn
     */
    public function paginateReturn(PDOStatement $stmt, int $page, int $qtdPerPage, int $offset): ModelPaginatedReturn
    {
        try {
            $result = $stmt->fetchAll();
            $count = $this->db->query('SELECT FOUND_ROWS() as `rows`')->fetch();
            return new ModelPaginatedReturn($result ?? [], $count->rows, $page, $qtdPerPage ? ($offset + $qtdPerPage) < $count->rows : false, false, "");
        } catch (\Throwable $th) {
            return new ModelPaginatedReturn([], 0, 0, false, true, $th->getMessage());
        }
    }

    /**
     * Constrói um filtro where sql
     *
     * @param array|object{
     *  'fieldName': array|float|int|bool|string
     * } $filters a key do array|objeto passado são os nomes dos campos e o valor é o valor utilizado, se for um array faz um filtro in automaticamente
     * 
     * @return string
     */
    protected static function buildWhere($filters): string
    {
        $arrFilters = [];
        foreach ($filters ?? [] as $name => $value) {
            if (is_array($value)) {
                $arrFilters[] = preg_replace('/\[.*?\]/', '', $name) . " in ('" . implode("', '", array_unique($value)) . "')";
            } elseif ($value || $value == "0") {
                $arrFilters[] = "$name = '$value'";
            }
        }

        return count($arrFilters) ? "WHERE " . implode(' and ', array_unique($arrFilters)) : "";
    }

    /**
     * Constrói um order sql
     *
     * @param $order [ 'nomeCampo' => 'asc'|'desc' ]
     * 
     * @return string
     */
    protected static function buildOrder($order): string
    {
        $orderArray = [];
        foreach ($order ?? [] as $key => $dir) {
            $orderArray[] = "$key $dir";
        }

        return count($orderArray) ? "ORDER BY " . implode(", ", $orderArray) : "";
    }

    /**
     * Constrói um order sql
     *
     * @param array|Pagination as keys da lista 
     * 
     * @return string
     */
    protected static function buildPagination($pagination, int $offset = null): string
    {
        if (!isset($pagination->limit) || !+$pagination->limit) return '';

        $offsetC = '';
        if ($offset) $offsetC = "OFFSET " . $offset;

        return "LIMIT $pagination->limit $offsetC";
    }

    /**
     * Buscar dinâmica em uma tabela
     *
     * @param null|array|object{
     *  'fieldName': array|float|int|bool|string
     * } $filters
     * @param $orderBy [ 'nomeCampo' => 'asc'|'desc' ]
     * @param null|Pagination $pagination
     * 
     * @return ModelPaginatedReturn
     */
    public function findBy($filters = [], $orderBy = [], Pagination $pagination = null): ModelPaginatedReturn
    {
        $offset = null;
        if (isset($pagination->limit) && isset($pagination->page) && !!+$pagination->page) $offset = $pagination->page * $pagination->limit;

        $whereC = self::buildWhere($filters);
        $orderC = self::buildOrder($orderBy);
        $paginationC = self::buildPagination($pagination, $offset);

        $sql = "SELECT SQL_CALC_FOUND_ROWS
            {$this->table}.*
        FROM {$this->table}
            $whereC
            $orderC
            $paginationC
        ";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $this->paginateReturn($query, ($pagination->page ?? 1), ($pagination->limit ?? 0), +$offset);
    }

    /**
     * Busca elemento um pelo id
     *
     * @param int $id
     * 
     * @return object|false
     */
    public function findById($id)
    {
        if (!$id) return false;
        $result = $this->findBy(["$this->table.id" => $id], null, null);

        return $result->data && count($result->data) ? $result->data[0] ?? false : false;
    }

    /**
     * Buscar um elemento
     *
     * @param null|array|object{'fieldName': array|float|int|bool|string} $filters
     * @param $orderBy [ 'nomeCampo' => 'asc'|'desc' ]
     * 
     * @return object|false
     */
    public function findOneBy($filters = [], $order = [])
    {
        $result = $this->findBy($filters, $order, null);

        return $result->data && count($result->data) ? $result->data[0] ?? false : false;
    }


    /**
     * Função de inserção dos dados na tabela notificada na model
     *
     * @param array $columns ['columnName' => 'columnValue']
     * @return ModelInsertReturn
     */
    public function insert(array $columns): ModelInsertReturn
    {
        foreach ($columns as $key => $value) {
            $columnArray[] = "`$key`";
            $columnArrayPDO[] = ":{$key}";
            $parameters[":{$key}"] = ($value !== "" ? $value : NULL);
        }

        $column = implode(",", $columnArray);
        $pdo = implode(",", $columnArrayPDO);

        $sql = "INSERT INTO {$this->table} ({$column}) VALUES ({$pdo})";
        $query = $this->db->prepare($sql);
        $responseQuery = $query->execute($parameters);
        $lastId = $this->db->lastInsertId();
        $item = $this->findById($lastId);

        return new ModelInsertReturn(!$responseQuery, '', $lastId, $item);
    }

    /**
     * Atualiza o registro
     *
     * @param array $columns ['columnName' => 'columnValue']
     * @param string $whereCol
     * @param string $whereVal
     * @param boolean $log = false se deve ou não gerar log
     * @return ModelReturn
     */
    public function update(array $columns, string $whereCol, string $whereVal): ModelReturn
    {
        foreach ($columns as $key => $value) {
            $columnArray[] = "`{$key}` = :{$key}";
            $parameters[":{$key}"] = ($value !== "" ? $value : NULL);
        }

        $parameters[':id'] = $whereVal;

        $column = implode(",", $columnArray);

        $sql = "UPDATE {$this->table} SET {$column} WHERE {$whereCol} = :id";

        $query = $this->db->prepare($sql);
        $responseQuery = $query->execute($parameters);

        return new ModelReturn(!$responseQuery, $responseQuery ? 'A item has been successfully edited.' : 'Error while editing this item');
    }

    /**
     * Roda um delete no sistema de acordo com as columns passadas pode ser um array, só o id ou um string do id
     * 
     * @param array|int|string $column
     * array = ['id' => 1]
     * int = 1 Id do registro
     * string = AND id = 1
     * 
     * @return ModelReturn
     */
    public function delete($column): ModelReturn
    {
        $where = '';
        $parameters = [];

        if (is_array($column)) {
            foreach ($column as $key => $value) {
                $where .= " AND {$key} = :{$key}";
                $parameters[":{$key}"] = $value;
            }
        } else if (is_numeric($column)) {
            $where .= " AND id = :id";
            $parameters[':id'] = $column;
        } else if (is_string($column)) {
            $where .= " $column";
        } else {
            return new ModelReturn(true, 'Invalid param');
        }
        $where = ltrim($where, ' AND');

        $sql = "DELETE FROM {$this->table} WHERE {$where}";
        $query = $this->db->prepare($sql);
        $responseQuery = $query->execute($parameters);

        return new ModelReturn(!$responseQuery, $responseQuery ? 'Item deleted successfully' : 'Error while deleting this item');
    }
}

/**
 * Classe que representa o retorno do projeto
 *
 * @property bool $error Se ocorreu algum erro capturado.
 * @property string $success_message Mensagem de sucesso da consulta.
 * @property string $error_message Mensagem de erro da consulta.
 */
class ModelReturn implements JsonSerializable
{
    protected $error = false;
    protected $message = '';
    private $success_message = 'Procedure completed successfully';
    private $error_message = 'Error while performing the procedure';

    public function __construct(bool $error, string $message = '')
    {
        if (!$message) $message = $error ? $this->error_message : $this->success_message;
        $this->error = $error;
        $this->message = $message;
    }

    /**
     * Getter genérico
     * 
     * @param string $property
     * @return void|mixed
     */
    public function __get(string $property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * Setter genérico
     * 
     * @param string $property
     * @return void
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public function __isset($property)
    {
        return property_exists($this, $property);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'error' => $this->error,
            'message' => $this->message,
        ];
    }
}

/**
 * Classe que representa o retorno paginado de uma consulta.
 *
 * @property int $count Quantidade total de registros retornados.
 * @property int $page Número da página atual.
 * @property array $data Dados retornados pela consulta.
 * @property bool $hasNext Indica se há mais páginas para serem consultadas.
 * @property string $success_message Mensagem de sucesso da consulta.
 * @property string $error_message Mensagem de erro da consulta.
 */
class ModelPaginatedReturn extends ModelReturn
{
    protected $count = 0;
    protected $page = 0;
    protected $data = [];
    protected $hasNext = false;
    private $success_message = 'Query executed successfully';
    private $error_message = 'Error while trying to perform the query';

    public function __construct(array $data = [], int $count = 0, int $page = 0, bool $hasNext = false, bool $error = false, string $message = '')
    {
        if (!$message) $message = $error ? $this->error_message : $this->success_message;
        parent::__construct($error, $message);

        $this->data = $data;
        $this->count = $count;
        $this->page = $page;
        $this->hasNext = $hasNext;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'error' => $this->error,
            'message' => $this->message,
            'count' => $this->count,
            'page' => $this->page,
            'data' => $this->data,
            'hasNext' => $this->hasNext,
        ];
    }
}

/**
 * Modelo padronizado de retorno de funções do projeto serve principalmente para auxiliar na padronização de retornos, autocomplete e nas correções e avisos dos senses
 *
 * @property int $lastId Id do item inserido.
 * @property object $item Dados do item inserido.
 * @property string $success_message Mensagem de sucesso da inserção.
 * @property string $error_message Mensagem de erro da inserção.
 */
class ModelInsertReturn extends ModelReturn
{
    protected $lastId = 0;
    protected $item;
    private $success_message = 'Item added successfully';
    private $error_message = 'Error while trying to add the item';

    public function __construct(bool $error, string $message = '', int $lastId = 0, object|false $item = false)
    {
        if (!$message) $message = $error ? $this->error_message : $this->success_message;
        parent::__construct($error, $message);

        $this->lastId = $lastId;
        $this->item = $item;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'error' => $this->error,
            'message' => $this->message,
            'lastId' => $this->lastId,
            'item' => $this->item,
        ];
    }
}

/**
 * Classe para representar a paginação de dados.
 * 
 * @property int $limit O limite de resultados por página.
 * @property int $page A página atual.
 * 
 */
class Pagination
{
    /**
     * Limite de resultados por página.
     *
     * @var int
     */
    public $limit = 0;

    /**
     * Página atual.
     *
     * @var int
     */
    public $page = 0;

    /**
     * Construtor da classe Pagination.
     *
     * @param int $limit O número máximo de resultados por página.
     * @param int $page O número da página atual.
     */
    public function __construct(int $limit, int $page = 1)
    {
        $this->limit = $limit;
        $this->page = $page;
    }
}


enum JoinType: string
{
    case INNER = 'inner';
    case LEFT = 'left';
    case RIGHT = 'right';
}

/**
 * Representa um objeto Join para ser utilizado em consultas SQL.
 */
class Join
{
    /**
     * Nome da Tabela
     */
    public string $table;

    /**
     * Alias da tabela
     */
    public string $as;

    /**
     * Tipo de Join
     */
    public JoinType $join;

    /**
     * Clausula on exemplo: b.id == a.id_b
     */
    public $on = '';

    /**
     * Cria um novo objeto Join.
     */
    public function __construct(string $table, JoinType $join, string $on, string $as = '', array $columns = [])
    {
        $this->table = $table;
        $this->join = $join;
        $this->on = $on;
        $this->as = $as;
    }
}
