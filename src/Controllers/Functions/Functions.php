<?php

namespace Controllers\Functions;

use Controllers\PublicController;
use Utilities\Context;
use Utilities\Paging;
use Dao\Functions\Functions as DaoFunctions;
use Views\Renderer;

class Functions extends PublicController
{
    private $partialName = "";
    private $status = "";
    private $orderBy = "";
    private $orderDescending = false;
    private $pageNumber = 1;
    private $itemsPerPage = 10;

    private $viewData = [];
    private $functions = [];
    private $functionsCount = 0;
    private $pages = 0;

    public function run(): void
    {
        $this->getParamsFromContext();
        $this->getParams();

        if (isset($_GET["clear"])) {
            $this->partialName = "";
            $this->status = "";
            $this->orderBy = "";
            $this->orderDescending = false;
            $this->pageNumber = 1;
        }

        $tmp = DaoFunctions::getFunctions(
            $this->partialName,
            $this->status,
            $this->orderBy,
            $this->orderDescending,
            $this->pageNumber - 1,
            $this->itemsPerPage
        );

        $this->functions = $tmp["functions"];
        $this->functionsCount = $tmp["total"];

        $this->pages = $this->functionsCount > 0
            ? ceil($this->functionsCount / $this->itemsPerPage)
            : 1;

        if ($this->pageNumber > $this->pages) {
            $this->pageNumber = $this->pages;
        }

        $this->setParamsToContext();
        $this->setParamsToView();

        Renderer::render("functions/functions", $this->viewData);
    }

    private function getParams(): void
    {
        $this->partialName = $_GET["partialName"] ?? $this->partialName;
        $this->status = $_GET["status"] ?? $this->status;
        $this->orderBy = $_GET["orderBy"] ?? $this->orderBy;
        $this->orderDescending = isset($_GET["orderDescending"])
            ? boolval($_GET["orderDescending"])
            : false;

        $this->pageNumber = isset($_GET["pageNum"])
            ? intval($_GET["pageNum"])
            : $this->pageNumber;

        $this->itemsPerPage = isset($_GET["itemsPerPage"])
            ? intval($_GET["itemsPerPage"])
            : $this->itemsPerPage;
    }

    private function getParamsFromContext(): void
    {
        $this->partialName = Context::getContextByKey("functions_partialName");
        $this->status = Context::getContextByKey("functions_status");
        $this->orderBy = Context::getContextByKey("functions_orderBy");
        $this->orderDescending = boolval(
            Context::getContextByKey("functions_orderDescending")
        );
        $this->pageNumber = intval(
            Context::getContextByKey("functions_page")
        );
        $this->itemsPerPage = intval(
            Context::getContextByKey("functions_itemsPerPage")
        );

        if ($this->pageNumber < 1) {
            $this->pageNumber = 1;
        }

        if ($this->itemsPerPage < 1) {
            $this->itemsPerPage = 10;
        }
    }

    private function setParamsToContext(): void
    {
        Context::setContext("functions_partialName", $this->partialName, true);
        Context::setContext("functions_status", $this->status, true);
        Context::setContext("functions_orderBy", $this->orderBy, true);
        Context::setContext("functions_orderDescending", $this->orderDescending, true);
        Context::setContext("functions_page", $this->pageNumber, true);
        Context::setContext("functions_itemsPerPage", $this->itemsPerPage, true);
    }

    private function setParamsToView(): void
    {
        $this->viewData["partialName"] = $this->partialName;
        $this->viewData["status"] = $this->status;
        $this->viewData["functions"] = $this->functions;
        $this->viewData["functionsCount"] = $this->functionsCount;
        $this->viewData["pages"] = $this->pages;
        $this->viewData["pageNum"] = $this->pageNumber;

        $this->viewData["pagination"] = Paging::getPagination(
            $this->functionsCount,
            $this->itemsPerPage,
            $this->pageNumber,
            "index.php?page=Functions_Functions",
            "Functions_Functions"
        );
    }
}