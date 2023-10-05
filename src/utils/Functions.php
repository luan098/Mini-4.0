<?php

namespace Mini\utils;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;

function returnJson(mixed $data = null): void
{
    (new JsonResponse($data))->send();
    exit();
}

/**
 * Redireciona com header location e ja encera a chamada com exit para evitar que dados fiquem no ar
 *
 * @param string $path caminho que sera redirecionado exemplo: "/home"
 * @param boolean $concatLocation se deve ou não concatenar o Location:
 * @param boolean $concatUrl Se deve ou não concatenar a url
 * @return void Não retorna nada só redireciona e encerra todas as chamadas com exit
 */
function redirect(string $path, bool $concatLocation = true, bool $concatUrl = true)
{
    header(($concatLocation ? 'location: ' : "") . ($concatUrl ? URL : '') . $path);
    exit;
}

/**
 * Retorna para a página anterior
 *
 * @param string|null $safeUrl Caso o return não seja possível por algum motivo é a url que ira retornar
 * @param array|null $post Futuramente re-preenchera os inputs do post caso sejam passados no parâmetro 
 * @return void Não retorna nada só redireciona e encerra todas as chamadas com exit
 */
function redirectReturn(string $safeUrl = 'home', array $post = null)
{
    $currentURL = str_replace('url=', '', $_SERVER['QUERY_STRING']);
    $currentURL = str_replace("/", '\/', $currentURL);

    $targetURL = $_SERVER['HTTP_REFERER'] ?? null;
    $sameURL = preg_match("/$currentURL/", $targetURL);
    if ($sameURL || is_null($targetURL)) $targetURL = URL . $safeUrl;

    header('Location: ' . $targetURL);

    // TODO: FALTA TERMINAR O PREENCHIMENTO POST MAS O RETORNO JÁ FUNCIONA
    // echo "<script>
    //     // Obtém todos os elementos <input>
    //     var inputs = document.querySelectorAll('input');

    //     // Armazena os valores dos inputs em localStorage
    //     for (var i = 0; i < inputs.length; i++) {
    //         localStorage.setItem(inputs[i].name, inputs[i].value);
    //     }

    //     history.back();
    // </script>"; // Implementação futura
    exit();
}

/**
 * Grava na session para exibir um toast no proximo carregamento de tela
 * * É essencial que toda ação do usuário tenha um retorno seja exibindo uma message de erro ou de sucesso sempre visando o feedback correto
 *
 * @param 'success'|'warning'|'error' $type tipo do toast (success => Verde para confirmações, warning => Amarelo para avisos, error => vermelho para erros)
 * @param string $message Mensagem do toast
 * @return void não retorna nada só grava a informação de exibição na session que sera capturada, tratada, exibida e excluída no novo load
 */
function toast(string $type, string $message)
{
    $_SESSION['toast'] = (object)[
        'icon' => $type,
        'title' => str_replace("'", '`', $message)
    ];
}

/**
 * Cria um toast com base no response passado usando o atributo error e message
 *
 * @param object $response {error: boolean, message: string}
 * @return boolean
 */
function toastResult(object $response)
{
    try {
        toast($response->error ? 'error' : 'success', $response->message);
    } catch (\Throwable $th) {
        return false;
    }

    return true;
}

/**
 * Cria um toast para tratamentos de erros capturados
 *
 * @param object $response {getMessage: function}
 * @return boolean
 */
function toastError(\Exception $th)
{
    try {
        toast('error', $th->getMessage());
    } catch (\Throwable $th) {
        return false;
    }

    return true;
}

/**
 * Retorna um array mergeado removendo registro duplicados
 * * FUNCIONA COM ARRAY DE OBJETOS
 * @param array $array array principal
 * @param array $itensToMerge itens para o merge
 * @return array array resultado
 */
function array_merge_unique(array $array, array $itensToMerge)
{
    if (!$itensToMerge || !$itensToMerge[0]) return $array;
    $array = array_merge($array, $itensToMerge);
    $array = array_unique(array_map('json_encode', $array));
    return array_map('json_decode', $array);
}

/**
 * Busca a posição de uma ou mais strings em outra string
 * !! Está função e do sistema, não é nativa da language
 * 
 * @param string $haystack A string onde iremos buscar
 * @param array|string $needles As strings a serem encontradas
 * 
 * @return int|bool A posição da primeira needle encontrada ou false
 */
function strpos_array(string $haystack, $needles): ?int
{
    if (!is_array($needles)) $needles = [$needles];

    foreach ($needles as $needle) {
        $pos = strpos($haystack, $needle);
        if ($pos !== false) return $pos;
    }
    return false;
}

function maskMoney(float|int $value, string $prefix = "¥ ", int $decimals = 2, string $thousandsSep = ",", string $decimalSep = "."): string
{
    if ($prefix && substr($prefix, -1) === ' ') $prefix = " $prefix";

    if (!is_numeric($value)) throw new InvalidArgumentException("Invalid numeric value provided.");

    if (!is_int($decimals) || $decimals < 0) throw new InvalidArgumentException("Invalid decimal precision provided.");

    $formattedValue = number_format($value, $decimals, $decimalSep, $thousandsSep);

    return $prefix . $formattedValue;
}

function unmaskMoney(string $maskedValue, string $thousandsSep = ",", string $decimalSep = "."): float
{
    $unmaskedValue = str_replace([$thousandsSep, ' '], '', ltrim($maskedValue));
    $unmaskedValue = str_replace($decimalSep, '.', $unmaskedValue);

    if (is_numeric($unmaskedValue)) return (float) $unmaskedValue;

    throw new InvalidArgumentException("Invalid masked value provided.");
}
