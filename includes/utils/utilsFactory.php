<?php
namespace es\ucm\fdi\aw\ePark;

class utilsFactory
{
    public static function createSuccessAlert($message)
    {
        $alert = <<<EOS
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            $message
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        EOS;
        return $alert;
    }

    public static function createCarrusel()
    {
        $alert = <<<EOS
        EOS;
        return $alert;
    }

    public static function createFormErrorsAlert($errors = array())
    {
        $message = '';

        $numErrores = count($errors);

        if ($numErrores == 1) {
            $message .= "<ul><li>" . $errors[0] . "</li></ul>";
        } else if ($numErrores > 1) {
            $message .= "<ul><li>";
            $message .= implode("</li><li>", $errors);
            $message .= "</li></ul>";
        }

        $alert = <<<EOS
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            $message
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        EOS;
        return $alert;
    }
}
