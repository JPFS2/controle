<?php

namespace App\Http;

class Request
{
    /** Metodo HTTP da requisição @var string */
    private $httpMethod;

    /** URI da PAGINA @var string */
    private $uri;

    /** Parâmetros da URL ($_GET) @var array  */
    private $queryParams = [];

    /** Variáveis recebidas do POST da pagina @var array  */
    private $postVars = [];

    /** Cabeçalho da requisição @var array  */
    private $headers = [];

    private $router;

    public function __construct($router)
    {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->setUri();
    }

    /**
     * @param mixed|string $uri
     */
    public function setUri()
    {
        $this->uri = $_SERVER['REQUEST_URI'];

        //Remover Gets da URI
        $xUri = explode('?',$this->uri);
        $this->uri = $xUri[0];
    }

    /**
     * @return mixed
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Método responsável por retornar o metodo HTTP da requisição
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Método responsável por retornar a URI da requisição
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Método responsável por retornar o Cabeçalho da requisição
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Método responsável por retornar os parametros da URL da requisição
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Método responsável por retornar as variaveis POST da requisição
     * @return array
     */
    public function getPostVars()
    {
        return $this->postVars;
    }
}