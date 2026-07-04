<?php

namespace Controllers\Functions;

use Controllers\PublicController;
use Dao\Functions\Functions as DaoFunctions;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

class FunctionController extends PublicController
{
    private $viewData = [];
    private $mode = "DSP";

    private $function = [
        "fncod" => "",
        "fndsc" => "",
        "fnest" => "ACT",
        "fntyp" => ""
    ];

    private $modeDescriptions = [
        "DSP" => "Detalle de %s",
        "INS" => "Nueva Función",
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
            Renderer::render("functions/function", $this->viewData);
        } catch (\Exception $e) {
            Site::redirectToWithMsg(
                "index.php?page=Functions_Functions",
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
            $id = $_GET["fncod"] ?? "";

            if ($id === "") {
                throw new \Exception("Falta código de función");
            }

            $this->function = DaoFunctions::getFunctionById($id);

            if (!$this->function) {
                throw new \Exception("Función no encontrada");
            }
        }
    }

    private function validateData(): bool
    {
        $errors = [];

        $this->function["fncod"] = trim($_POST["fncod"] ?? "");
        $this->function["fndsc"] = trim($_POST["fndsc"] ?? "");
        $this->function["fnest"] = $_POST["fnest"] ?? "ACT";
        $this->function["fntyp"] = trim($_POST["fntyp"] ?? "");

        if ($this->mode === "INS") {
            if ($this->function["fncod"] === "") {
                $errors["fncod_error"] = "El código es obligatorio";
            } else {
                $existing = DaoFunctions::getFunctionById($this->function["fncod"]);

                if ($existing) {
                    $errors["fncod_error"] = "El código ya existe";
                }
            }
        }

        if (Validators::IsEmpty($this->function["fndsc"])) {
            $errors["fndsc_error"] = "La descripción es obligatoria";
        }

        if (Validators::IsEmpty($this->function["fntyp"])) {
            $errors["fntyp_error"] = "El tipo es obligatorio";
        }

        if (!in_array($this->function["fnest"], ["ACT", "INA"])) {
            $errors["fnest_error"] = "Estado inválido";
        }

        if (count($errors) > 0) {
            foreach ($errors as $k => $v) {
                $this->function[$k] = $v;
            }
            return false;
        }

        return true;
    }

    private function handlePost(): void
    {
        switch ($this->mode) {
            case "INS":
                DaoFunctions::insertFunction(
                    $this->function["fncod"],
                    $this->function["fndsc"],
                    $this->function["fnest"],
                    $this->function["fntyp"]
                );
                break;

            case "UPD":
                DaoFunctions::updateFunction(
                    $this->function["fncod"],
                    $this->function["fndsc"],
                    $this->function["fnest"],
                    $this->function["fntyp"]
                );
                break;

            case "DEL":
                DaoFunctions::deleteFunction(
                    $this->function["fncod"]
                );
                break;
        }

        Site::redirectToWithMsg(
            "index.php?page=Functions_Functions&clear=1",
            "Operación realizada correctamente"
        );
    }

    private function setViewData(): void
    {
        $this->viewData["mode"] = $this->mode;

        $this->viewData["FormTitle"] = sprintf(
            $this->modeDescriptions[$this->mode],
            $this->function["fncod"]
        );

        $this->viewData["function"] = $this->function;
        $this->viewData["readonly"] = $this->readonly;
        $this->viewData["showCommitBtn"] = $this->showCommitBtn;
    }
}