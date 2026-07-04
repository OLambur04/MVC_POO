<?php

namespace Controllers\Users;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Users\Users as DaoUsers;
use Utilities\Site;
use Utilities\Validators;

class User extends PublicController
{
    private $viewData = [];
    private $mode = "DSP";

    private $modeDescriptions = [
        "DSP" => "Detalle de %s",
        "INS" => "Nuevo Usuario",
        "UPD" => "Editar %s",
        "DEL" => "Eliminar %s"
    ];

    private $readonly = "";
    private $showCommitBtn = true;

    private $user = [
        "usercod" => 0,
        "useremail" => "",
        "username" => "",
        "userest" => "ACT",
        "usertipo" => ""
    ];

    public function run(): void
    {
        try {
            $this->getData();

            if ($this->isPostBack()) {
                if ($this->validateData()) {
                    $this->handlePostAction();
                }
            }

            $this->setViewData();
            Renderer::render("users/user", $this->viewData);

        } catch (\Exception $ex) {
            Site::redirectToWithMsg(
                "index.php?page=Users_Users",
                $ex->getMessage()
            );
        }
    }

    private function getData()
    {
        $this->mode = $_GET["mode"] ?? "NOF";

        if (!isset($this->modeDescriptions[$this->mode])) {
            throw new \Exception("Modo inválido");
        }

        $this->readonly = $this->mode === "DEL" ? "readonly" : "";
        $this->showCommitBtn = $this->mode !== "DSP";

        if ($this->mode !== "INS") {
            $usercod = intval($_GET["usercod"] ?? 0);

            $this->user = DaoUsers::getUserById($usercod);

            if (!$this->user) {
                throw new \Exception("Usuario no encontrado");
            }
        }
    }

    private function validateData()
    {
        $errors = [];

        $this->user["usercod"] = intval($_POST["usercod"] ?? 0);
        $this->user["useremail"] = strval($_POST["useremail"] ?? "");
        $this->user["username"] = strval($_POST["username"] ?? "");
        $this->user["userest"] = strval($_POST["userest"] ?? "");
        $this->user["usertipo"] = strval($_POST["usertipo"] ?? "");

        if ($this->mode === "DEL") return true;

        if (Validators::IsEmpty($this->user["useremail"])) {
            $errors["useremail_error"] = "Email requerido";
        }

        if (Validators::IsEmpty($this->user["username"])) {
            $errors["username_error"] = "Nombre requerido";
        }

        if (!in_array($this->user["userest"], ["ACT", "INA"])) {
            $errors["userest_error"] = "Estado inválido";
        }

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                $this->user[$key] = $value;
            }
            return false;
        }

        return true;
    }

    private function handlePostAction()
    {
        switch ($this->mode) {
            case "INS":
                DaoUsers::insertUser(
                    $this->user["useremail"],
                    $this->user["username"],
                    $this->user["userest"],
                    $this->user["usertipo"]
                );
                break;

            case "UPD":
                DaoUsers::updateUser(
                    $this->user["usercod"],
                    $this->user["useremail"],
                    $this->user["username"],
                    $this->user["userest"],
                    $this->user["usertipo"]
                );
                break;

            case "DEL":
                DaoUsers::deleteUser($this->user["usercod"]);
                break;
        }

        Site::redirectToWithMsg("index.php?page=Users_Users", "Operación realizada");
    }

    private function setViewData()
    {
        $this->viewData["mode"] = $this->mode;
        $this->viewData["FormTitle"] = sprintf(
            $this->modeDescriptions[$this->mode],
            $this->user["useremail"]
        );

        $this->viewData["readonly"] = $this->readonly;
        $this->viewData["showCommitBtn"] = $this->showCommitBtn;
        $this->viewData["user"] = $this->user;
    }
}