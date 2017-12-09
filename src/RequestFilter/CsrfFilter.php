<?php
namespace Gap\Base\RequestFilter;

use Gap\Security\CsrfProvider;

class CsrfFilter extends RequestFilterBase
{
    public function filter(): void
    {
        if ($this->request->isMethod('POST')) {
            $postToken = $this->request->request->get('csrfToken');
            $sessionToken = $this->request->getSession()->get('csrfToken');

            if (!hash_equals($postToken, $sessionToken)) {
                // todo
                throw new \Exception("Error Request");
            }
        }
    }
}
