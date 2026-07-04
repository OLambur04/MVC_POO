<?php

namespace Controllers\Roles;

use Controllers\PublicController;
use Dao\Roles\Roles as DaoRoles;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

class Role extends PublicController
{
    private $viewData = [];
    private $mode = "DSP";

    private $role = [
        "rolescod" => "",
        "rolesdsc" => "",
        "rolesest" => "ACT"
    ];

    private $modeDescriptions = [
        "DSP" => "Detalle de %s",
        "INS" => "Nuevo Rol",
        "UPD" => "Editar %s",
        "DEL" => "Eliminar %s"
    ];

    private $readonly = "";
    private $showCommitBtn = true;

    public function run(): void
    {
        try {
            $this->getData();

            if ($this->isPostBack()) {
                if ($this->validateData()) {
                    $this->handlePost();
                    return;
                }
            }

            $this->setViewData();
            Renderer::render("roles/role", $this->viewData);
        } catch (\Exception $e) {
            Site::redirectToWithMsg(
                "index.php?page=Roles_Roles",
                $e->getMessage()
            );
        }
    }

    private function getData(): void
    {
        $this->mode = $_GET["mode"] ?? "NOF";

        if (!isset($this->modeDescriptions[$this->mode])) {
            throw new \Exception("Modo inválido");
        }

        $this->readonly = $this->mode === "DEL" ? "readonly" : "";
        $this->showCommitBtn = $this->mode !== "DSP";

        if ($this->mode !== "INS") {
            $id = $_GET["rolescod"] ?? "";

            if ($id === "") {
                throw new \Exception("Falta código de rol");
            }

            $this->role = DaoRoles::getRoleById($id);

            if (!$this->role) {
                throw new \Exception("Rol no encontrado");
            }
        }
    }

    private function validateData(): bool
    {
        $errors = [];

        $this->role["rolescod"] = trim($_POST["rolescod"] ?? "");
        $this->role["rolesdsc"] = trim($_POST["rolesdsc"] ?? "");
        $this->role["rolesest"] = $_POST["rolesest"] ?? "ACT";

        if ($this->mode === "INS") {

            if ($this->role["rolescod"] === "") {
                $errors["rolescod_error"] = "El código es obligatorio";
            } else {

                $existing = \Dao\Roles\Roles::getRoleById($this->role["rolescod"]);

                if ($existing) {
                    $errors["rolescod_error"] = "El código ya existe";
                }
            }
        }

        if (Validators::IsEmpty($this->role["rolesdsc"])) {
            $errors["rolesdsc_error"] = "La descripción es obligatoria";
        }

        if (!in_array($this->role["rolesest"], ["ACT", "INA"])) {
            $errors["rolesest_error"] = "Estado inválido";
        }

        if (count($errors) > 0) {
            foreach ($errors as $k => $v) {
                $this->role[$k] = $v;
            }
            return false;
        }

        return true;
    }

    private function handlePost(): void
    {
        switch ($this->mode) {
            case "INS":
                DaoRoles::insertRole(
                    $this->role["rolescod"],
                    $this->role["rolesdsc"],
                    $this->role["rolesest"]
                );
                break;

            case "UPD":
                DaoRoles::updateRole(
                    $this->role["rolescod"],
                    $this->role["rolesdsc"],
                    $this->role["rolesest"]
                );
                break;

            case "DEL":
                DaoRoles::deleteRole($this->role["rolescod"]);
                break;
        }

        Site::redirectToWithMsg(
            "index.php?page=Roles_Roles&clear=1",
            "Operación realizada correctamente"
        );
    }

    private function setViewData(): void
    {
        $this->viewData["mode"] = $this->mode;
        $this->viewData["FormTitle"] = sprintf(
            $this->modeDescriptions[$this->mode],
            $this->role["rolescod"]
        );

        $this->viewData["role"] = $this->role;
        $this->viewData["readonly"] = $this->readonly;
        $this->viewData["showCommitBtn"] = $this->showCommitBtn;
    }
}
