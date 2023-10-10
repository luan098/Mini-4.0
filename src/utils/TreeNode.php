<?php

namespace Mini\utils;

/**
 * Representa um estado de nó de uma árvore com diversas propriedades customizáveis.
 * * Abstraído do padrão de objeto de arvore vindo do jsTree
 * @see https://www.jstree.com/docs/json/
 */
class NodeState
{
    /** @var boolean */
    public $opened;

    /** @var boolean */
    public $disabled;

    /** @var boolean */
    public $selected;

    public function __construct(bool $opened = false, bool $disabled = false, bool $selected = false)
    {
        $this->opened = $opened;
        $this->disabled = $disabled;
        $this->selected = $selected;
    }
}

/**
 * Representa um nó de uma árvore com diversas propriedades customizáveis.
 * * Abstraído do padrão de objeto de arvore vindo do jsTree
 * @see https://www.jstree.com/docs/json/
 */
class TreeNode
{
    /**
     * ID único do nó.
     * 
     * @var integer
     */
    public $id;

    /**
     * Texto do nó.
     * 
     * @var string
     */
    public $text;

    /**
     * Dados do nó. Os dados brutos utilizados para criar ele
     * 
     * @var object[]
     */
    public $data;

    /**
     * Ícone do nó.
     * 
     * @var string
     */
    public $icon;

    /**
     * Estado do nó.
     * 
     * @var NodeState
     */
    public $state;

    /**
     * Filhos do nó.
     * 
     * @var object[]
     */
    public $children;

    /**
     * Atributos para o elemento LI gerado.
     * 
     * @var array
     */
    public $li_attr;

    /**
     * Atributos para o elemento A gerado.
     * 
     * @var array
     */
    public $a_attr;

    /**
     * Construtor da classe TreeNode.
     *
     * @param object $data dados brutos utilizados para montar o nó.
     * @param string $id Id do nó.
     * @param string $text Texto do nó.
     * @param array $children Filhos do nó. Opcional, padrão: [].
     * @param bool $disabled Indica se o nó está desabilitado. Opcional, padrão: false.
     * @param bool $opened Indica se o nó está aberto. Opcional, padrão: false.
     * @param bool $selected Indica se o nó está selecionado. Opcional, padrão: false.
     * @param string $icon Ícone do nó. Opcional, padrão: "".
     * @param array $li_attr Atributos para o elemento LI gerado. Opcional, padrão: [].
     * @param array $a_attr Atributos para o elemento A gerado. Opcional, padrão: [].
     */
    public function __construct(
        object $data,
        int $id,
        string $text,
        array $children = [],
        bool $disabled = false,
        bool $opened = false,
        bool $selected = false,
        string $icon = "",
        array $li_attr = [],
        array $a_attr = []
    ) {
        $this->id = $id; // ID único gerado automaticamente
        $this->text = $text;
        $this->data = $data;
        $this->icon = $icon ? $icon : (empty($children) ? "fas fa-inbox" : "fas fa-box-open");
        $this->state = new NodeState(
            $opened,
            $disabled,
            $selected
        );
        $this->children = $children;
        $this->li_attr = $li_attr;
        $this->a_attr = $a_attr;
    }
}
