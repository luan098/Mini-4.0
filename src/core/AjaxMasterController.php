<?php

namespace Mini\core;

use Exception;
use ReflectionMethod;

/** Recebe todas as requisições ajax que comecem AjaxMaster, navega a partir da pasta src e chama qualquer Class do projeto desde controllers até models e libs
 * ! Os parâmetros que a função pede devem ser passados via POST
 * * Estrutura URL Correta: AjaxMaster/CaminhoDePastas/NomeDoArquivoComAClass/MétodoDentroDaClass
 * * Exemplo: AjaxMaster/model/Sales/getSaleData
 * ! Os dois últimos itens da url sempre precisam ser /NomeDoArquivoComAClass/MétodoDentroDaClass
 */
class AjaxMasterController
{
    /**
     * No construtor é iniciado uma session e verifica se o usuário está conectado ou não para aceitar a requisição
     */
    public function __construct()
    {
        if (!$_SESSION['user']->id ?? true) {
            echo json_encode(['error' => true, 'message' => 'Sessão Inválida', 'data' => false]);
            exit;
        }
    }


    /**
     * Captura qualquer chamada para o AjaxMasterController e trata essa chamada encaminhando ela para os devidos arquivos do sistema e 
     * tratando toda a requisição adequadamente, devolve a requisição com um echo para que o ajax trabalhe com uma resposta padronizada
     *
     * @param [string] $method
     * @param [array] $args
     */
    public function __call($method, $args)
    {
        try {
            $method = end($args);
            array_pop($args);

            $fileName = end($args);
            array_pop($args);

            $route = strtolower(implode(DIRECTORY_SEPARATOR, $args)) . DIRECTORY_SEPARATOR . $fileName;

            if (!file_exists(APP . $route . '.php')) throw new Exception("Requisição ajax inválida, file não encontrado.");

            $cls = "\\Mini\\" . strtolower(implode("\\", $args)) . "\\" . $fileName;
            if (!method_exists($cls, $method)) throw new Exception("Requisição ajax inválida, método inexistente.");
            self::formatParams();

            $reflector = new ReflectionMethod($cls, $method);
            $params = $reflector->getParameters();

            foreach ($params as $param) {
                if ($param->getType() == 'array' && isset($_POST[$param->name]) && $_POST[$param->name]) {
                    $_POST[$param->name] = (array)$_POST[$param->name];
                }
            }

            if ($reflector->isStatic()) {
                $return = $cls::$method(...array_values($_POST));
            } else {
                $classInstance = new $cls;
                $return = call_user_func_array([$classInstance, $method], $_POST);
            }

            echo json_encode(
                [
                    'error' => false,
                    'message' => 'Requisição realizada com sucesso.',
                    'data' => $return
                ]
            );
        } catch (Exception $e) {
            echo json_encode(
                [
                    'error' => true,
                    'message' => $e->getMessage(),
                    'data' => false
                ]
            );
        }

        exit;
    }

    /**
     * Função responsável por tratar e converter todas as entradas passadas no post para serem tratadas como parâmetros de funções adequados
     */
    private static function formatParams()
    {
        $newPost = [];
        foreach ($_POST as $key => $param) {
            $newPost[$key] = json_decode($param);
        }

        $_POST = $newPost;
    }
}
