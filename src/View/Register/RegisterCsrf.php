<?php
namespace Gap\Base\View\Register;

use Gap\Http\Request;
use Gap\Security\CsrfProvider;
use Foil\Engine;

class RegisterCsrf implements RegisterInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function register(Engine $engine): void
    {
        $self = $this;

        $engine->registerFunction(
            'csrf',
            function () use ($self) {
                $token = $self->getCsrfToken();
                return "<input type=\"hidden\" name=\"csrfToken\" value=\"$token\">";
            }
        );

        $engine->registerFunction(
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
