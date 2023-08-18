<?php


namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Usuario
{
  /** @var int $codusur */
  public $codusur;

  /** @var string $usuario */
  public $usuario;

  /** @var string $senha */
  public $senha;

  /** @var string $email */
  public $email;

  public static function getUserByEmail($email){
      return (new Database('cadusuario'))->select('email = "'.$email.'"')->fetchObject(self::class);
  }


}