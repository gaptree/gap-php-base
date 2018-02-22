<?php
namespace phpunit\Gap\Base\Ui;

use Gap\Http\Response;
use Gap\Base\Ui\UiBase;

class FetchArticleUi extends UiBase
{
    public function show(): Response
    {
        return $this->view('fetchArticle');
    }
}
