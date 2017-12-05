<?php
namespace Gap\Base\Controller\View;

use Gap\Http\Request;
use Gap\Security\CsrfProvider;

class RegisterCsrf extends RegisterBase
{
    public function register()
    {
        $self = $this;

        $this->engine->registerFunction(
            'csrf',
            function () use ($self) {
                $token = $self->token();
                return "<input type=\"hidden\" name=\"token\" value=\"$token\">";
            }
        );

        $this->engine->registerFunction(
            'token',
            function () use ($self) {
                return $self->token();
            }
        );
    }

    // todo try protected
    public function token(): string
    {
        $session = $this->request->getSession();
        if ($token = $session->get('token')) {
            return $token;
        }

        $csrf = new CsrfProvider();
        $token = $csrf->token();
        $session->set('token', $token);
        return $token;
    }
}
