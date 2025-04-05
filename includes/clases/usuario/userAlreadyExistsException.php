<?php

namespace es\ucm\fdi\aw\ePark;

/**
 * Excepción lanzada cuando un usuario ya existe en el sistema.
 */
final class UserAlreadyExistsException extends \Exception
{
    /**
     * Constructor de la excepción UserAlreadyExistsException.
     *
     * @param string $message Mensaje de error (opcional).
     * @param int $code Código de error (opcional).
     * @param \Throwable|null $previous Excepción previa para encadenamiento (opcional).
     */
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
