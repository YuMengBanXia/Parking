<?php
namespace es\ucm\fdi\aw\ePark;

class loginForm extends formBase
{
    public function __construct()
    {
        /* Contrucción del formulario donde 
        formID = loginForm 
        action = loginForm.php (porque no se especifica)
        */
        parent::__construct('loginForm');
    }

    protected function CreateFields($datos)
    {
        $nombreUsuario = '';
        if ($datos) {
            $nombreUsuario = isset($datos['nombreUsuario']) ? $datos['nombreUsuario'] : $nombreUsuario;
        }
        $html = <<<EOF
        <fieldset>
            <legend>Iniciar sesión</legend>
            <p><label>Usuario:</label> <input type="text" name="nombreUsuario" value="$nombreUsuario"/></p>
            <p><label>Contraseña:</label> <input type="password" name="password" /></p>
            <button type="submit" name="login">Entrar</button>
        </fieldset>
        EOF;

        return $html;
    }

    protected function Process($datos)
    {
        $result = array();

        // nombreUsuario
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');

        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($nombreUsuario)) {
            $result[] = "El nombre de usuario no puede estar vacío";
        }

        // password
        $password = trim($datos['password'] ?? '');

        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($password)) {
            $result[] = "El password no puede estar vacío.";
        }

        // Si no hay errores intentar loguear
        if (count($result) === 0) {
            
            $userDTO = new userDTO(0, $nombreUsuario, $password);

            $userAppService = userAppService::GetSingleton();

            $foundedUserDTO = $userAppService->login($userDTO);

            if (! $foundedUserDTO) {
                // No se da pistas a un posible atacante
                $result[] = "El usuario o el password no coinciden";
            } else {
                $_SESSION["login"] = true;
                $_SESSION["nombre"] = $nombreUsuario;

                $result = 'index.php';
            }
        }

        return $result;
    }
}
