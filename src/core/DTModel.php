<?php

namespace Mini\core;

use InvalidArgumentException;

/**
 * DataTable Model with the getDatatable functions
 */
abstract class DTModel extends Model
{
    protected array $joins = [];

    public function __construct(string $table, array $joins = [])
    {
        $this->joins = $joins;

        parent::__construct($table);
    }

    /**
     * Função para trabalhar com datatable js, feita para interpretar todas as configurações do datatable e moldar a consulta de acordo com 
     * !! deve funcionar normalmente qual qualquer passagem do jquery porem o parâmetro searchable foi alterado para dar maior compatibilidade
     * columns: [{
     *        searchable: { // searchable pode ser um boolean ou um objeto, caso seja um objeto você pode passar o parâmetro regex e escolher um regex para limpar o filtro (somente para o campo que tiver este regex)
     *            regex: '[^0-9]+'
     *        }
     * },
     * 
     * @version 0.2.3 Versão protótipo
     * @author https://github.com/luan098
     * @param object $data
     * @return object
     */
    public function getDatatable(object $data)
    {
        try {
            $arrSearch = [];
            $arrColumns = [];
            $arrFilters = [];
            $arrJoins = [];
            $alreadyJoined = [];

            foreach ($data->columns as $column) {
                $as = $column->value != $column->data ? "as $column->data" : "";
                if (!stripos($column->value, '.')) $column->value = ($column->join ? $column->join : $this->table) . ".$column->value";
                if (!stripos($column->field, '.')) $column->field = ($column->join ? $column->join : $this->table) . ".$column->field";

                // Montando as colunas
                $column->sql = "$column->value $as";
                $arrColumns[] = $column->sql;

                // Montando o filtro search
                if (!!$column->searchable && $data->search->value) {
                    if (isset($column->search->regex) && $column->search->regex) {
                        $arrSearch[] = "ucase($column->value) LIKE ucase(REGEXP_REPLACE('{$data->search->value}', '{$column->search->regex}',''))";
                    } else {
                        $arrSearch[] = "ucase($column->value) LIKE ucase('%{$data->search->value}%')";
                    }
                }

                // Montando os joins solicitados nas colunas
                if (isset($column->join) && $column->join) {
                    foreach ($this->joins as $validJoin) {
                        if ($column->join && ($validJoin->table == $column->join || (isset($validJoin->as) && $validJoin->as == $column->join)) && !in_array(($validJoin->table . ($validJoin->as ?? "")), $alreadyJoined)) {
                            $alreadyJoined[] = $validJoin->table . ($validJoin->as ?? "");
                            $arrJoins[] = $validJoin;
                        }
                    }
                }
            }

            // Montando os filtros
            foreach ($data->filters ?? [] as $name => $filter) {
                if (!stripos($name, '.')) $name = "$this->table.$name";
                $index = array_search($name, array_column($data->columns, 'field'));

                $column = $index ? $data->columns[$index]->field : $name;

                if (!is_array($filter)) {
                    if (is_array($filter->value) && count($filter->value)) {
                        $arrFilters[] = preg_replace('/\[.*?\]/', '', $column) . " in ('" . implode("', '", array_unique($filter->value)) . "')";
                    } elseif ($filter->value || $filter->value == "0") {
                        $arrFilters[] = "$column $filter->validation '$filter->value'";
                    }
                } else {
                    $arrSubFilters = [];
                    foreach ($filter as $subFilter) {
                        if (is_array($subFilter->value) && count($subFilter->value)) {
                            $arrSubFilters[] = preg_replace('/\[.*?\]/', '', $column) . " in ('" . implode("', '", array_unique($subFilter->value)) . "')";
                        } elseif ($subFilter->value || $subFilter->value == "0") {
                            $arrSubFilters[] = "$column $subFilter->validation '$subFilter->value'";
                        }
                    }

                    if (count($arrSubFilters)) {
                        $arrFilters[] = '(' . implode(' and ', array_unique($arrSubFilters)) . ')';
                    }
                }
            }

            /** Montando order */
            $orderArray = [];
            foreach ($data->order as $order) {
                $sqlColumn = $data->columns[$order->column]->field;
                $orderArray[] = "$sqlColumn $order->dir";
            }

            $orderBy = "ORDER BY " . implode(", ", $orderArray);
            $sqlColumns = implode(', ', array_unique($arrColumns));
            $joins = count($arrJoins) ? self::buildJoin($arrJoins, true) : '';
            $filters = count($arrFilters) ? 'and ' . implode(' and ', array_unique($arrFilters)) : '';
            $searchFilter = count($arrSearch) ? 'and (' . implode(' OR ', $arrSearch) . ')' : '';

            $offset = $data->start ?? 0;

            $sql     = "SELECT SQL_CALC_FOUND_ROWS {$sqlColumns} from {$this->table} {$joins} where true $filters $searchFilter $orderBy limit $data->length OFFSET $offset";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            $recordsTotalFiltered = $this->db->query('SELECT FOUND_ROWS() as `rows`')->fetch();
            $recordsTotal = $this->db->query("SELECT count(*) as `rows` from {$this->table}")->fetch();
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return (object) [
            'draw'            => uniqid(),
            'recordsFiltered' => + ($recordsTotalFiltered->rows ?? 0), // total de registros depois da filtragem
            'recordsTotal'    => + ($recordsTotal->rows ?? 0), // total de registros da tabela inteira
            'data'            => ($result ?? []),
            'error'           => ($error ?? false),
        ];
    }

    function buildJoin($joins, $justJoinClause = false)
    {
        $clause = "";
        $columnsJoined = [];

        foreach ($joins as $join) {
            $table = property_exists($join, 'table') ? $join->table : null;
            $on = property_exists($join, 'on') ? $join->on : null;

            foreach ($join->columns ?? [] as $cName) {
                $columnsJoined[] = "{$join->table}.{$cName} as {$join->table}_{$cName}";
            }

            if ($table && $on) {
                $as = isset($join->as) && $join->as ? "as $join->as" : '';
                $clause .= " LEFT JOIN {$table} {$as} ON {$on}";
            } else {
                throw new InvalidArgumentException('Invalid Object. Need the properties "table" and "on".');
            }
        }

        $columnsJoined = array_unique($columnsJoined);
        $columnsClause = count($columnsJoined) ? ", " . implode(', ', $columnsJoined) : "";

        if ($justJoinClause) return $clause;
        return (object)['columns' => $columnsClause, 'join' => $clause];
    }
}
