<?php

namespace Controllers\{{MODULE}};

use Controllers\PublicController;
use Views\Renderer;
use Dao\{{MODULE}}\{{DAOCLASS}} as Model;

class {{LISTCLASS}} extends PublicController
{
    public function run(): void
    {
        $viewData = [];

        $viewData["data"] = Model::getAll();

        Renderer::render("{{MODULE}}/list", $viewData);
    }
}