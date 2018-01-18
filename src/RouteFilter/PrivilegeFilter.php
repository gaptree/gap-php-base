<?php
namespace Gap\Base\RouteFilter;

use Gap\Base\Exception\NotLoginException;
use Gap\Base\Exception\NoPermissionException;

class PrivilegeFilter extends RouteFilterBase
{
    public function filter()
    {
        $config = $this->app->getConfig();
        $access = $this->route->getAccess();
        $privilege = $config->get("secure.privilege.$access");

        if (!$privilege) {
            return;
        }

        $serviceClass = $config->get('secure.fetchUserService');
        if (!class_exists($serviceClass)) {
            throw new \Exception('secure.fetchUserService not-found: ' . $serviceClass);
        }

        $fetchUserService = new $serviceClass($this->app);

        $userId = $this->request->get('session')->get('userId');
        if (!$userId) {
            throw new NotLoginException("access-$access-need-login");
        }
        $user = $fetchUserService->fetchByUserId($userId);
        if (!$user) {
            throw new NotLoginException("userId[$userId]-not-found");
        }

        if ($user->privilege < $privilege) {
            throw new NoPermissionException("access-$access");
        }
    }
}
