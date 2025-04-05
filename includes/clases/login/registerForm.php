<?php

namespace es\ucm\fdi\aw\ePark;

class registerForm extends formBase
{
    public function __construct()
    {
        // Contrucción del formulario 
        // formID = registerForm
        // action = registerForm.php
        parent::__construct('registerForm');
    }

    protected function CreateFields($datos)
    {
        // Valores iniciales de los campos
        $nombreUsuario = '';
        $dniUsuario = '';
        $tipoUsuario = 'cliente';
        $opcionCliente = '';
        $opcionPropietario = '';
        $opcionAdministrador = '';

        // Recuperar valores enviados previamente (si existen)
        if ($datos) {
            $nombreUsuario = $datos['nombreUsuario'] ?? $nombreUsuario;
            $dniUsuario = $datos['dni'] ?? $dniUsuario;
            $tipoUsuario = $datos['tipoUsuario'] ?? $tipoUsuario;

            $opcionCliente = ($tipoUsuario === "cliente") ? "selected" : "";
            $opcionPropietario = ($tipoUsuario === "propietario") ? "selected" : "";
            $opcionAdministrador = ($tipoUsuario === "administrador") ? "selected" : "";
        }

        // Generar el formulario HTML
        $html = <<<EOF
        <fieldset>
            <legend>Registrar Usuario</legend>
            <p><label>DNI:</label> <input type="text" name="dni" value="$dniUsuario" /></p>
            <p><label>Nombre de Usuario:</label> <input type="text" name="nombreUsuario" value="$nombreUsuario" /></p>
            <p><label>Contraseña:</label> <input type="password" name="password" /></p>
            <p><label>Repetir Contraseña:</label> <input type="password" name="rePassword" /></p>
            <p>
                <label>Tipo de Usuario:</label>
                <select name="tipoUsuario">
                    <option value="cliente" $opcionCliente>Cliente</option>
                    <option value="propietario" $opcionPropietario>Propietario</option>
                    <option value="administrador" $opcionAdministrador>Administrador</option>
                </select>
            </p>
            <button type="submit" name="register">Registrar</button>
        </fieldset>
        EOF;

        return $html;
    }

    protected function Process($datos)
    {
        $result = [];

        // Validación del DNI
        $dniUsuario = trim($datos['dni'] ?? '');
        $dniUsuario = filter_var($dniUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($dniUsuario)) {
            $result[] = "El DNI no puede estar vacío.";
        } elseif (strlen($dniUsuario) !== 9) {
            $result[] = "El DNI debe tener exactamente 9 caracteres.";
        }

        // Validación del nombre de usuario
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($nombreUsuario)) {
            $result[] = "El nombre de usuario no puede estar vacío.";
        }

        // Validación de la contraseña
        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($password)) {
            $result[] = "La contraseña no puede estar vacía.";
        }

        // Validación de la repetición de la contraseña
        $rePassword = trim($datos['rePassword'] ?? '');
        $rePassword = filter_var($rePassword, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($password !== $rePassword) {
            $result[] = "Las contraseñas introducidas no coinciden.";
        }

        // Validación del tipo de usuario
        if (isset($datos['tipoUsuario'])) {
            $tipoUsuario = filter_var($datos['tipoUsuario'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (!in_array($tipoUsuario, ['cliente', 'propietario', 'administrador'])) {
                $result[] = "El tipo de usuario seleccionado no es válido.";
            }
        } else {
            $result[] = "El tipo de usuario no puede estar vacío.";
        }

        // Si no hay errores, procesar el registro
        if (count($result) === 0) {
            try{
                $userDTO = new \es\ucm\fdi\aw\ePark\userDTO($dniUsuario, $nombreUsuario, $password, $tipoUsuario);
                $userAppService = userAppService::GetSingleton();
    
                $createdUserDTO = $userAppService->create($userDTO);

                $userAppService->login($userDTO);
                $_SESSION["login"] = true;
                $_SESSION["nombre"] = $nombreUsuario;
                $_SESSION["dni"] = $dniUsuario;
                $_SESSION["tipo"] = $tipoUsuario;
                $app = Aplicacion::getInstance();
                $mensajes = ["Usuario $nombreUsuario registrado correctamente."];
                $app->putAtributoPeticion('mensajes', $mensajes);
                return 'index.php'; // Redirección en caso de éxito
            }
            catch(userAlreadyExistException $e)
            {
                $result[] = $e->getMessage();
            }
        }

        return $result; // Devolver errores si los hay
    }
}
