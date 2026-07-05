<?php

namespace Dao\Generator;

use Dao\Table;

class Generator extends Table
{
    public static function getTableStructure(string $tableName)
    {
        try {

            $data = self::obtenerRegistros("SHOW COLUMNS FROM {$tableName}", []);

            if (!$data || count($data) === 0) {
                throw new \Exception("EMPTY_TABLE");
            }

            return $data;
        } catch (\PDOException $ex) {
            throw new \Exception("TABLE_NOT_FOUND");
        }
    }

    public static function generateCRUD(string $tableName)
    {
        $fields = self::getTableStructure($tableName);

        $className = ucfirst($tableName);
        $module = strtolower($tableName);

        $baseDir = "src";

        $daoTpl = file_get_contents(__DIR__ . "/../../GeneratorTemplates/crud.dao.tpl");
        $listCtrlTpl = file_get_contents(__DIR__ . "/../../GeneratorTemplates/crud.listcontroller.tpl");
        $formCtrlTpl = file_get_contents(__DIR__ . "/../../GeneratorTemplates/crud.formcontroller.tpl");
        $listViewTpl = file_get_contents(__DIR__ . "/../../GeneratorTemplates/crud.listview.tpl");
        $formViewTpl = file_get_contents(__DIR__ . "/../../GeneratorTemplates/crud.formview.tpl");

        if (!$daoTpl || !$listCtrlTpl || !$formCtrlTpl || !$listViewTpl || !$formViewTpl) {
            throw new \Exception("TEMPLATES_MISSING");
        }

        $daoTpl = str_replace(
            ["{{CLASS}}", "{{MODULE}}", "{{TABLE}}"],
            [$className, $module, $tableName],
            $daoTpl
        );

        $listCtrlTpl = str_replace(
            ["{{MODULE}}", "{{DAOCLASS}}", "{{LISTCLASS}}", "{{LISTVAR}}"],
            [$module, $className, $className, $module],
            $listCtrlTpl
        );

        $formCtrlTpl = str_replace(
            ["{{MODULE}}", "{{FORMCLASS}}"],
            [$module, $className . "Form"],
            $formCtrlTpl
        );

        $listViewTpl = str_replace(["{{TITLE}}"], [$className], $listViewTpl);
        $formViewTpl = str_replace(["{{TITLE}}"], [$className], $formViewTpl);

        $daoDir = "{$baseDir}/Dao/{$module}";
        $ctrlDir = "{$baseDir}/Controllers/{$module}";
        $viewDir = "{$baseDir}/Views/templates/{$module}";

        if (!is_dir($daoDir)) mkdir($daoDir, 0777, true);
        if (!is_dir($ctrlDir)) mkdir($ctrlDir, 0777, true);
        if (!is_dir($viewDir)) mkdir($viewDir, 0777, true);

        file_put_contents("{$daoDir}/{$className}.php", $daoTpl);
        file_put_contents("{$ctrlDir}/{$className}List.php", $listCtrlTpl);
        file_put_contents("{$ctrlDir}/{$className}Form.php", $formCtrlTpl);
        file_put_contents("{$viewDir}/list.view.tpl", $listViewTpl);
        file_put_contents("{$viewDir}/form.view.tpl", $formViewTpl);

        return [
            "dao" => "{$daoDir}/{$className}.php",
            "list_controller" => "{$ctrlDir}/{$className}List.php",
            "form_controller" => "{$ctrlDir}/{$className}Form.php",
            "list_view" => "{$viewDir}/list.view.tpl",
            "form_view" => "{$viewDir}/form.view.tpl"
        ];
    }
}
