<?php

namespace Controllers\Users;

use Controllers\PublicController;
use Utilities\Context;
use Utilities\Paging;
use Dao\Users\Users as DaoUsers;
use Views\Renderer;

class Users extends PublicController
{
    private $partialName = "";
    private $status = "";
    private $userType = "";

    private $orderBy = "";
    private $orderDescending = false;

    private $pageNumber = 1;
    private $itemsPerPage = 10;

    private $viewData = [];
    private $users = [];
    private $usersCount = 0;
    private $pages = 0;

    public function run(): void
    {
        $this->getParamsFromContext();
        $this->getParams();

        $tmpUsers = DaoUsers::getUsers(
            $this->partialName,
            $this->status,
            $this->userType,
            $this->orderBy,
            $this->orderDescending,
            $this->pageNumber - 1,
            $this->itemsPerPage
        );

        $this->users = $tmpUsers["users"];
        $this->usersCount = $tmpUsers["total"];

        $this->pages = $this->usersCount > 0
            ? ceil($this->usersCount / $this->itemsPerPage)
            : 1;

        if ($this->pageNumber > $this->pages) {
            $this->pageNumber = $this->pages;
        }

        $this->setParamsToContext();
        $this->setParamsToDataView();

        Renderer::render("users/users", $this->viewData);
    }

    private function getParams(): void
    {
        $this->partialName = $_GET["partialName"] ?? $this->partialName;

        $this->status = $_GET["status"] ?? $this->status;
        if ($this->status === "EMP") {
            $this->status = "";
        }

        $this->userType = $_GET["userType"] ?? $this->userType;
        if ($this->userType === "EMP") {
            $this->userType = "";
        }

        $this->orderBy = $_GET["orderBy"] ?? $this->orderBy;
        if ($this->orderBy === "clear") {
            $this->orderBy = "";
        }

        $this->orderDescending = isset($_GET["orderDescending"])
            ? boolval($_GET["orderDescending"])
            : false;

        $this->pageNumber = isset($_GET["pageNum"])
            ? intval($_GET["pageNum"])
            : $this->pageNumber;

        $this->itemsPerPage = isset($_GET["itemsPerPage"])
            ? intval($_GET["itemsPerPage"])
            : $this->itemsPerPage;

        if ($this->pageNumber < 1) $this->pageNumber = 1;
        if ($this->itemsPerPage < 1) $this->itemsPerPage = 10;
    }

    private function getParamsFromContext(): void
    {
        $this->partialName = Context::getContextByKey("users_partialName");
        $this->status = Context::getContextByKey("users_status");
        $this->userType = Context::getContextByKey("users_userType");
        $this->orderBy = Context::getContextByKey("users_orderBy");
        $this->orderDescending = boolval(Context::getContextByKey("users_orderDescending"));
        $this->pageNumber = intval(Context::getContextByKey("users_page"));
        $this->itemsPerPage = intval(Context::getContextByKey("users_itemsPerPage"));

        if ($this->pageNumber < 1) $this->pageNumber = 1;
        if ($this->itemsPerPage < 1) $this->itemsPerPage = 10;
    }

    private function setParamsToContext(): void
    {
        Context::setContext("users_partialName", $this->partialName, true);
        Context::setContext("users_status", $this->status, true);
        Context::setContext("users_userType", $this->userType, true);
        Context::setContext("users_orderBy", $this->orderBy, true);
        Context::setContext("users_orderDescending", $this->orderDescending, true);
        Context::setContext("users_page", $this->pageNumber, true);
        Context::setContext("users_itemsPerPage", $this->itemsPerPage, true);
    }

    private function setParamsToDataView(): void
    {
        $this->viewData["partialName"] = $this->partialName;
        $this->viewData["status"] = $this->status;
        $this->viewData["userType"] = $this->userType;

        $this->viewData["users"] = $this->users;
        $this->viewData["usersCount"] = $this->usersCount;
        $this->viewData["pages"] = $this->pages;
        $this->viewData["pageNum"] = $this->pageNumber;

        if ($this->orderBy !== "") {
            $orderKey = "Order" . ucfirst($this->orderBy);
            $orderNoKey = "OrderBy" . ucfirst($this->orderBy);

            $this->viewData[$orderNoKey] = true;

            if ($this->orderDescending) {
                $orderKey .= "Desc";
            }

            $this->viewData[$orderKey] = true;
        }

        $statusKey = "status_" . ($this->status === "" ? "EMP" : $this->status);
        $this->viewData[$statusKey] = "selected";

        $typeKey = "userType_" . ($this->userType === "" ? "EMP" : $this->userType);
        $this->viewData[$typeKey] = "selected";

        $pagination = Paging::getPagination(
            $this->usersCount,
            $this->itemsPerPage,
            $this->pageNumber,
            "index.php?page=Users_Users",
            "Users_Users"
        );

        $this->viewData["pagination"] = $pagination;
    }
}