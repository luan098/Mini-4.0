<?php

namespace Mini\model;

use Mini\core\Model;
use Mini\core\ModelReturn;
use Mini\core\Pagination;
use Mini\utils\TreeNode;

class Menus extends Model
{
    const TABLE = 'menus';

    function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Busca os elementos em formato de arvore TreeNode
     *
     * @param int $idFather
     * @param int $status
     * @return TreeNode[]
     */
    public function getTreeForConfig($idFather = null, $status = null)
    {
        $menus = $this->findByFatherForConfig($idFather, $status);

        $result = [];
        foreach ($menus as $menu) {
            $children = $this->getTreeForConfig($menu->id, $status);

            array_push(
                $result,
                new TreeNode(
                    $menu,
                    $menu->id,
                    $menu->name,
                    $children,
                    !+$menu->status,
                    false,
                    false,
                    $menu->icon
                )
            );
        }

        return $result;
    }

    function findByFatherForConfig($idFather, $status = null)
    {
        $status = $status === 0 || $status ? "AND m.status = $status" : "";
        $sql = "SELECT
                    *
                FROM {$this->table} m
                WHERE true
                    $status
                    and m.id_menu_father " . ($idFather === null ? "is null" : " = $idFather") . "
                ORDER BY m.name";

        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    /**
     * Busca os elementos em formato de arvore TreeNode
     *
     * @param int $idFather
     * @param int $status
     * @return TreeNode[]
     */
    public function getTree($idFather = null, $status = null, $ignoreAccess = false)
    {
        $menus = $this->findByFather($idFather, $status, $ignoreAccess);

        $result = [];
        foreach ($menus as $menu) {
            $children = $this->getTree($menu->id, $status);

            if (!$menu->page && empty($children)) continue;

            array_push(
                $result,
                new TreeNode(
                    $menu,
                    $menu->id,
                    $menu->name,
                    $children,
                    !+$menu->status,
                    false,
                    false,
                    $menu->icon
                )
            );
        }

        return $result;
    }

    function findByFather($idFather, $status = null, $ignoreAccess = false)
    {
        $status = $status === 0 || $status ? "AND m.status = $status" : "";
        $idMenuParentCondition = $idFather === null ? "is null" : " = $idFather";

        $sql = "SELECT
                    m.*
                FROM {$this->table} m
                WHERE true
                    $status
                    AND m.id_menu_father $idMenuParentCondition
                ORDER BY m.name";

        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    function getMenuFathers()
    {

        $sql = "SELECT
                    *
                FROM {$this->table} m
                WHERE m.id_menu_father is null and (m.page = '#' OR m.page = '' OR m.page is null)";

        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    public function getMenuByRoute($page)
    {
        $sql = "SELECT
                    *
                FROM $this->table
                WHERE status = TRUE
                AND page like (:page)";

        $query = $this->db->prepare($sql);
        $parameters = array(':page' => $page);
        $query->execute($parameters);

        return $query->fetch();
    }

    /**
     * Deleta um menu validando os erros e limitações, capturando e tratando eles
     *
     * @param int $idCC
     * @return ModelReturn
     */
    public function safeDelete(int $idMenu): ModelReturn
    {
        try {
            $children = $this->findBy(['id_menu_father' => $idMenu], null, new Pagination(1))->data;

            if (isset($children[0])) return new ModelReturn(true, 'Você não pode deletar um menu com filhos associados.');
            return $this->delete($idMenu);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            if (strpos($message, 'Integrity constraint violation')) {
                $message = 'Você não pode deletar um menu com vínculos.';
            }

            return new ModelReturn(true, $message);
        }
    }
}
