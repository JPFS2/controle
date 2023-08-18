<?php

namespace App\Http;

class Response
{
    /** Código do Status HTTP @var int  */
    private $httpCode = 200;

    /** Cabeçalho da Resposta @var array  */
    private $headers = [];

    /** Tipo de conteudo da Resposta @var string */
    private $contentType = 'text/html';

    /** Conteudo da Resposta @var mixed */
    private $content;

    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    public function addHeader($key,$value){
        $this->headers[$key] = $value;
    }

    /**
     * Metodo responsavel opr alterar o content type do response
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('ContentType',$contentType);
    }

    private function sendHeaders(){
        http_response_code($this->httpCode);
        foreach ($this->headers as $key => $value){
            header($key.': '.$value);
        }
    }

    public function sendResponse(){
        $this->sendHeaders();

        switch ($this->contentType){
            case 'text/html' :
                echo $this->content;
                exit();
        }
    }

}