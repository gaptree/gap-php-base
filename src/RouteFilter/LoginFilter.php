<?php
namespace Gap\Base\RouteFilter;

use Gap\Base\Exception\NotLoginException;

class LoginFilter extends RouteFilterBase
{
    public function filter()
    {
        if ('login' === $this->route->getAccess()) {
            if (!$this->request->getSession()->get('userId')) {
                throw new NotLoginException();
            }
        }
    }
}
