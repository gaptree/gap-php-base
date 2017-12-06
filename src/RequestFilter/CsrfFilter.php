<?php
namespace Gap\Base\RequestFilter;

use Gap\Security\CsrfProvider;

class CsrfFilter extends RequestFilterBase
{
    public function filter(): void
    {
        if ($this->request->isMethod('POST')) {
            // todo change token -> csrf_token
            $postToken = $this->request->request->get('csrf_token');
            $sessionToken = $this->request->getSession()->get('csrf_token');

            if (!hash_equals($postToken, $sessionToken)) {
                // todo
                throw new \Exception("Error Request");
            }
        }
    }
}
