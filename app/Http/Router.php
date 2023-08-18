<?php

namespace App\Http;

use Closure;
use Exception;
use ReflectionFunction;
use App\Http\Middleware\Queue as MiddlewareQueue;

class Router
{
    /** URL completa do projeto @var string  */
    private $url = '';

    /** Prefixo de todas as Rotas @var string  */
    private $prefix = '';

    /** Armazena todas as  @var array  */
    private $routes = [];

    /** Instancia da classe Request @var Request */
    private $request;

    public function __construct($url)
    {
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    private function setPrefix(){
        $parseUrl = parse_url($this->url);
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Metodo Responsavel por adicionar uma rota na Classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params = []){
        // Validação dos Parametros
        foreach ($params as $key => $value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        /** Middlewares da rota */
        $params['middlewares'] = $params['middlewares'] ?? [];

        // Variaveis da Rota
        $params['variables'] = [];

        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable,$route,$matches)){
            $route = preg_replace($patternVariable,'(.*?)',$route);
            $params['variables'] = $matches[1];
        }

        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';
        $this->routes[$patternRoute][$method] = $params;

    }

    public  function getUri(){
        $uri = $this->request->getUri();

        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];

        return end($xUri);
    }

    private function getRoute(){
        //URI
        $uri = $this->getUri();

        $httpMethod = $this->request->getHttpMethod();
        //VALIDA AS ROTAS
        foreach ($this->routes as $patternRoute => $method){
            //VERIFICA A URI
            if(preg_match($patternRoute,$uri,$matches)){
                if(isset($method[$httpMethod])){
                    // REMOVE PRIMEIRA POSIÇAO
                    unset($matches[0]);

                    //Variaveis Processadas
                    $keys = $method[$httpMethod]['variables'];
                    $method[$httpMethod]['variables'] = array_combine($keys,$matches);
                    $method[$httpMethod]['variables']['request'] = $this->request;

                    return $method[$httpMethod];
                }
                throw new Exception("Metodo Não permitido",405);
            }


        }
        throw new Exception("URL não encontrada",404);
    }

    /**
     * Metodo Responsavel por definir uma Rota do metodo GET
     * @param string$route
     * @param array $params
     */
    public function get($route,$params = []){
        return $this->addRoute('GET',$route,$params);
    }

    public function post($route,$params = []){
        return $this->addRoute('POST',$route,$params);
    }

    public function PUT($route,$params = []){
        return $this->addRoute('PUT',$route,$params);
    }
    public function delete($route,$params = []){
        return $this->addRoute('DELETE',$route,$params);
    }

    public function run(){
        try {
            $route = $this->getRoute();

            if(!isset($route['controller'])){
                throw new Exception("URL não processada",500);

            }
            /** Argumentos da Função @var array $args */
            $args = [];

            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            return (new MiddlewareQueue($route['middlewares'],$route['controller'], $args))->next($this->request);
        }catch (Exception $e){
            return new Response($e->getCode(), $e->getMessage());
        }
    }

    // Metodo responsavel por retornar a URL atual
    public function getCurrentUrl(){
        return $this->url.$this->getUri();
    }

    public function redirect($route){
        $url = $this->url.$route;
        header('location: '.$url);
        exit();

    }
}