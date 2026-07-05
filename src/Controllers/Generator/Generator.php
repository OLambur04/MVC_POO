<?php

namespace Controllers\Generator;

use Controllers\PublicController;
use Dao\Generator\Generator as GeneratorDao;
use Views\Renderer;

class Generator extends PublicController
{
    private $viewData = [];

    public function run(): void
    {
        $this->viewData["error"] = "";
        $this->viewData["success"] = "";
        $this->viewData["fields"] = [];
        $this->viewData["generated"] = null;
        $this->viewData["tableName"] = "";

        if ($this->isPostBack()) {

            $tableName = trim($_POST["tableName"] ?? "");

            if ($tableName === "") {
                $this->viewData["error"] = "Debe ingresar el nombre de la tabla";
            } else {

                try {

                    $this->viewData["tableName"] = $tableName;

                    $this->viewData["fields"] =
                        GeneratorDao::getTableStructure($tableName);

                    $this->viewData["generated"] =
                        GeneratorDao::generateCRUD($tableName);

                    $this->viewData["success"] =
                        "CRUD generado correctamente para '{$tableName}'";
                } catch (\Exception $ex) {

                    $msg = $ex->getMessage();

                    if ($msg === "TABLE_NOT_FOUND") {
                        $this->viewData["error"] = "No se encontró la tabla '{$tableName}'";
                    } elseif ($msg === "EMPTY_TABLE") {
                        $this->viewData["error"] = "La tabla '{$tableName}' no tiene estructura válida";
                    } elseif ($msg === "NAV_NOT_FOUND") {
                        $this->viewData["error"] = "No se encontró el archivo de menú";
                    } else {
                        $this->viewData["error"] = "No se pudo generar el CRUD";
                    }
                }
            }
        }

        Renderer::render("generator/generator", $this->viewData);
    }
}
