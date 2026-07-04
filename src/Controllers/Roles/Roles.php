<?php

namespace Controllers\Roles;

use Controllers\PublicController;
use Utilities\Context;
use Utilities\Paging;
use Dao\Roles\Roles as DaoRoles;
use Views\Renderer;

class Roles extends PublicController
{
    private $partialName = "";
    private $status = "";
    private $orderBy = "";
    private $orderDescending = false;
    private $pageNumber = 1;
    private $itemsPerPage = 10;

    private $viewData = [];
    private $roles = [];
    private $rolesCount = 0;
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

        $tmp = DaoRoles::getRoles(
            $this->partialName,
            $this->status,
            $this->orderBy,
            $this->orderDescending,
            $this->pageNumber - 1,
            $this->itemsPerPage
        );

        $this->roles = $tmp["roles"];
        $this->rolesCount = $tmp["total"];

        $this->pages = $this->rolesCount > 0
            ? ceil($this->rolesCount / $this->itemsPerPage)
            : 1;

        if ($this->pageNumber > $this->pages) {
            $this->pageNumber = $this->pages;
        }

        $this->setParamsToContext();
        $this->setParamsToView();

        Renderer::render("roles/roles", $this->viewData);
    }

    private function getParams(): void
    {
        $this->partialName = $_GET["partialName"] ?? $this->partialName;
        $this->status = $_GET["status"] ?? $this->status;

        $this->orderBy = $_GET["orderBy"] ?? $this->orderBy;
        $this->orderDescending = isset($_GET["orderDescending"]) ? boolval($_GET["orderDescending"]) : false;

        $this->pageNumber = isset($_GET["pageNum"]) ? intval($_GET["pageNum"]) : $this->pageNumber;
        $this->itemsPerPage = isset($_GET["itemsPerPage"]) ? intval($_GET["itemsPerPage"]) : $this->itemsPerPage;
    }

    private function getParamsFromContext(): void
    {
        $this->partialName = Context::getContextByKey("roles_partialName");
        $this->status = Context::getContextByKey("roles_status");
        $this->orderBy = Context::getContextByKey("roles_orderBy");
        $this->orderDescending = boolval(Context::getContextByKey("roles_orderDescending"));
        $this->pageNumber = intval(Context::getContextByKey("roles_page"));
        $this->itemsPerPage = intval(Context::getContextByKey("roles_itemsPerPage"));

        if ($this->pageNumber < 1) $this->pageNumber = 1;
        if ($this->itemsPerPage < 1) $this->itemsPerPage = 10;
    }

    private function setParamsToContext(): void
    {
        Context::setContext("roles_partialName", $this->partialName, true);
        Context::setContext("roles_status", $this->status, true);
        Context::setContext("roles_orderBy", $this->orderBy, true);
        Context::setContext("roles_orderDescending", $this->orderDescending, true);
        Context::setContext("roles_page", $this->pageNumber, true);
        Context::setContext("roles_itemsPerPage", $this->itemsPerPage, true);
    }

    private function setParamsToView(): void
    {
        $this->viewData["partialName"] = $this->partialName;
        $this->viewData["status"] = $this->status;
        $this->viewData["roles"] = $this->roles;
        $this->viewData["rolesCount"] = $this->rolesCount;
        $this->viewData["pages"] = $this->pages;
        $this->viewData["pageNum"] = $this->pageNumber;

        $this->viewData["pagination"] = Paging::getPagination(
            $this->rolesCount,
            $this->itemsPerPage,
            $this->pageNumber,
            "index.php?page=Roles_Roles",
            "Roles_Roles"
        );
    }
}