<?php
namespace phpunit\Gap\Base;

use PHPUnit\Framework\TestCase;

use Gap\Base\HttpHandler;
use Gap\Routing\RouteCollection;
use Gap\Routing\RouteCollectionLoader;
use Gap\Routing\Router;

class HttpHandlerTest extends TestCase
{
    public function testHttpHandler(): void
    {
        $app = $this->createMock('Gap\Base\App');


        $siteManager = $this->getSiteManager();
        $router = $this->getRouter();

        $request = $this->getRequest();


        $httpHandler = new HttpHandler(
            $app,
            $siteManager,
            $router
        );

        $response = $httpHandler->handle($request);

        $this->assertEquals(
            ['welcome' => 'ok'],
            json_decode($response->getContent(), true)
        );
    }

    protected function getRequest()
    {
        $request = $this->createMock('Gap\Http\Request');

        $request->method('getHttpHost')->will($this->returnValue('www.gaptree.com'));
        $request->method('getPathInfo')->will($this->returnValue('/a/particle-zcode'));
        $request->method('getMethod')->will($this->returnValue('GET'));

        return $request;
    }

    protected function getSiteManager()
    {
        $siteManager = $this->createMock('Gap\Http\SiteManager');
        $siteManager->method('getSite')
            ->will($this->returnValue('www'));
        return $siteManager;
    }

    protected function getRouter()
    {
        //$router = $this->createMock('Gap\Routing\Router');
        $router = new Router();

        $loader = new RouteCollectionLoader();
        $collection = new RouteCollection();
        $collection
            ->site('www')
            ->access('public')
            ->get(
                '/a/{zcode:[a-zA-Z0-9-]+}',
                'fetchArticle',
                'phpunit\Gap\Base\Ui\FetchArticleUi@show'
            );

        $loader->loadCollection($collection, 'article');

        $router->load($loader->getRouterData());

        return $router;
    }
}
