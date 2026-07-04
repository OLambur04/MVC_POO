<?php

namespace Dao\Functions;

use Dao\Table;

class Functions extends Table
{
    public static function getFunctions($partialName, $status, $orderBy, $desc, $page, $itemsPerPage)
    {
        $sql = "SELECT fncod, fndsc, fnest, fntyp FROM funciones";
        $count = "SELECT COUNT(*) as total FROM funciones";

        $where = [];
        $params = [];

        if ($partialName !== "") {
            $where[] = "fndsc LIKE :name";
            $params["name"] = "%$partialName%";
        }

        if ($status !== "") {
            $where[] = "fnest = :status";
            $params["status"] = $status;
        }

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
            $count .= " WHERE " . implode(" AND ", $where);
        }

        if (in_array($orderBy, ["fncod", "fndsc", "fntyp"])) {
            $sql .= " ORDER BY $orderBy " . ($desc ? "DESC" : "");
        }

        $total = self::obtenerUnRegistro($count, $params)["total"];

        $sql .= " LIMIT " . ($page * $itemsPerPage) . ", $itemsPerPage";

        return [
            "functions" => self::obtenerRegistros($sql, $params),
            "total" => $total
        ];
    }

    public static function getFunctionById($id)
    {
        return self::obtenerUnRegistro(
            "SELECT * FROM funciones WHERE fncod = :id",
            ["id" => $id]
        );
    }

    public static function insertFunction($cod, $dsc, $est, $typ)
    {
        return self::executeNonQuery(
            "INSERT INTO funciones (fncod, fndsc, fnest, fntyp)
             VALUES (:cod, :dsc, :est, :typ)",
            compact("cod", "dsc", "est", "typ")
        );
    }

    public static function updateFunction($cod, $dsc, $est, $typ)
    {
        return self::executeNonQuery(
            "UPDATE funciones
             SET fndsc = :dsc,
                 fnest = :est,
                 fntyp = :typ
             WHERE fncod = :cod",
            compact("cod", "dsc", "est", "typ")
        );
    }

    public static function deleteFunction($cod)
    {
        return self::executeNonQuery(
            "DELETE FROM funciones WHERE fncod = :cod",
            ["cod" => $cod]
        );
    }
}