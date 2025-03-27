<?php

namespace es\ucm\fdi\aw\ePark;

class registerForm extends formBase
{
    public function __construct()
    {
        /* Contrucción del formulario donde 
        formID = registerForm 
        action = registerForm.php
        */
        parent::__construct('registerForm');
    }

    protected function CreateFields($datos)
    {
        // Cookies de algunos campos de texto
        $nombreUsuario = '';
        $dniUsuario = '';
        if ($datos) {
            $nombreUsuario = isset($datos['nombreUsuario']) ? $datos['nombreUsuario'] : $nombreUsuario;
            $dniUsuario = isset($datos['dni']) ? $datos['dni'] : $dniUsuario;
        }

        // Formulario de registro
        $html = <<<EOF
        <fieldset>
            <legend>Register</legend>
            <p><label>DNI:</label> <input type="text" name="dni" value="$dniUsuario"/></p>
            <p><label>Nombre de Usuario:</label> <input type="text" name="nombreUsuario" value="$nombreUsuario"/></p>
            <p><label>Contraseña:</label> <input type="password" name="password" /></p>
            <p><label>Repetición contraseña:</label> <input type="password" name="rePassword" /></p>
            <p>
                <label>Tipo de usuario:</label>
                <select name="tipoUsuario">
                    <option value="0">Cliente</option>
                    <option value="1">Propietario</option>
                </select>
            </p>
            <button type="submit" name="login">Entrar</button>
        </fieldset>
        EOF;

        return $html;
    }


    protected function Process($datos)
    {
        $result = array();

        $dniUsuario = trim($datos['dni'] ?? '');
        $dniUsuario = filter_var($dniUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($dniUsuario)) {
            $result[] = "El DNI no puede estar vacío";
        }

        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($nombreUsuario)) {
            $result[] = "El nombre de usuario no puede estar vacío";
        }

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($password)) {
            $result[] = "El password no puede estar vacío.";
        }

        $rePassword = trim($datos['rePassword'] ?? '');
        $rePassword = filter_var($rePassword, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($password !== $rePassword) {
            $result[] = "El password no coincide.";
        }

        // Usuario tipo cliente por defecto
        $tipoUsuario = $datos['tipoUsuario'] ?? 0;

        if (count($result) === 0) {

            $userDTO = new userDTO($dniUsuario, $nombreUsuario, $password, $tipoUsuario);

            $userAppService = userAppService::GetSingleton();
            $createdUserDTO = $userAppService->create($userDTO);

            if (! $createdUserDTO) {
                $result[] = "Error en el proceso de creación del usuario";
            } else {
                $$userAppService->login($userDTO);
                $_SESSION["login"] = true;
                $_SESSION["nombre"] = $nombreUsuario;
                $app = Aplicacion::getInstance();
                $mensajes = ["Usuario $nombreUsuario registrado correctamente"];
                $app->putAtributoPeticion('mensajes', $mensajes);
                $result = 'index.php';
            }
        }

        return $result;
    }
}
