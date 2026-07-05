<?php

namespace Controllers\{{MODULE}};

use Controllers\PublicController;
use Views\Renderer;

class {{FORMCLASS}} extends PublicController
{
    public function run(): void
    {
        Renderer::render("{{MODULE}}/form", []);
    }
}