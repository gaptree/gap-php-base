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
                $token = $self->getCsrfToken();
                return "<input type=\"hidden\" name=\"csrfToken\" value=\"$token\">";
            }
        );

        $this->engine->registerFunction(
            'csrfToken',
            function () use ($self) {
                return $self->getCsrfToken();
            }
        );
    }

    // todo try protected
    public function getCsrfToken(): string
    {
        $session = $this->request->getSession();
        if ($csrfToken = $session->get('csrfToken')) {
            return $csrfToken;
        }

        $csrf = new CsrfProvider();
        $csrfToken = $csrf->token();
        $session->set('csrfToken', $csrfToken);
        return $csrfToken;
    }
}
