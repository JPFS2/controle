<?php

namespace App\Http\Middleware;

class Queue
{
    /** Mapeamento do Meddleware @var array  */
    private static $map = [];

    /** Mapeamento de Meddleware que seram usado em todas rotas  @var array  */
    private static $default = [];

    /** Fila de Middlewares a serem executados @var array  */
    private $middleware = [];

    /** Função de execução do Controlador @var Closure */
    private $controller;

    /** Argumentos da função do controlador @var array  */
    private $controllerArgs = [];

    /**
     * @param array $middleware
     * @param Closure $controller
     * @param array $controllerArgs
     */
    public function __construct($middleware,$controller,$controllerArgs)
    {
        $this->middleware = array_merge(self::$default,$middleware);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Metodo responsavel por definir o mapeamento de middlewares
     * @param array $map
     */
    public static function setMap(array $map)
    {
        self::$map = $map;
    }

    /**
     * @param array $default
     */
    public static function setDefault(array $default)
    {
        self::$default = $default;
    }

    public function next($request){

        // Verifica se a fila esta vazia
        if(empty($this->middleware)) return call_user_func_array($this->controller,$this->controllerArgs);

        //Middleware
        $middleware = array_shift($this->middleware);

        if(!isset(self::$map[$middleware])){
            throw new \Exception("Problemas Com o Middleware da requisição",500);
        }

        //Next
        $queue = $this;
        $next = function ($request) use ($queue){
            return $queue->next($request);
        };

        return (new self::$map[$middleware])->handle($request,$next);
    }

}