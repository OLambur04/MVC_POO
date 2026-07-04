<?php

namespace Dao\Roles;

use Dao\Table;

class Roles extends Table
{
    public static function getRoles($partialName, $status, $orderBy, $desc, $page, $itemsPerPage)
    {
        $sql = "SELECT rolescod, rolesdsc, rolesest FROM roles";
        $count = "SELECT COUNT(*) as total FROM roles";

        $where = [];
        $params = [];

        if ($partialName !== "") {
            $where[] = "rolesdsc LIKE :name";
            $params["name"] = "%$partialName%";
        }

        if ($status !== "") {
            $where[] = "rolesest = :status";
            $params["status"] = $status;
        }

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
            $count .= " WHERE " . implode(" AND ", $where);
        }

        if (in_array($orderBy, ["rolescod", "rolesdsc"])) {
            $sql .= " ORDER BY $orderBy " . ($desc ? "DESC" : "");
        }

        $total = self::obtenerUnRegistro($count, $params)["total"];

        $sql .= " LIMIT " . ($page * $itemsPerPage) . ", $itemsPerPage";

        return [
            "roles" => self::obtenerRegistros($sql, $params),
            "total" => $total
        ];
    }

    public static function getRoleById($id)
    {
        return self::obtenerUnRegistro(
            "SELECT * FROM roles WHERE rolescod = :id",
            ["id" => $id]
        );
    }

    public static function insertRole($cod, $dsc, $est)
    {
        return self::executeNonQuery(
            "INSERT INTO roles (rolescod, rolesdsc, rolesest)
             VALUES (:cod, :dsc, :est)",
            compact("cod", "dsc", "est")
        );
    }

    public static function updateRole($cod, $dsc, $est)
    {
        return self::executeNonQuery(
            "UPDATE roles
             SET rolesdsc = :dsc, rolesest = :est
             WHERE rolescod = :cod",
            compact("cod", "dsc", "est")
        );
    }

    public static function deleteRole($cod)
    {
        return self::executeNonQuery(
            "DELETE FROM roles WHERE rolescod = :cod",
            ["cod" => $cod]
        );
    }
}