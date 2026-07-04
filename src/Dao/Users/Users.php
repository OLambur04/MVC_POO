<?php

namespace Dao\Users;

use Dao\Table;

class Users extends Table
{
    public static function getUsers(
        string $partialName = "",
        string $status = "",
        string $userType = "",
        string $orderBy = "",
        bool $orderDescending = false,
        int $page = 0,
        int $itemsPerPage = 10
    ) {
        $sql = "SELECT * FROM usuario";
        $sqlCount = "SELECT COUNT(*) as count FROM usuario";
        $conditions = [];
        $params = [];

        if ($partialName !== "") {
            $conditions[] = "username LIKE :name";
            $params["name"] = "%$partialName%";
        }

        if ($status !== "") {
            $conditions[] = "userest = :status";
            $params["status"] = $status;
        }

        if ($userType !== "") {
            $conditions[] = "usertipo = :type";
            $params["type"] = $userType;
        }

        if ($conditions) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
            $sqlCount .= " WHERE " . implode(" AND ", $conditions);
        }

        $orderable = ["usercod", "username", "useremail"];
        if (in_array($orderBy, $orderable)) {
            $sql .= " ORDER BY $orderBy " . ($orderDescending ? "DESC" : "");
        }

        $total = self::obtenerUnRegistro($sqlCount, $params)["count"];
        $sql .= " LIMIT " . ($page * $itemsPerPage) . ", $itemsPerPage";

        return [
            "users" => self::obtenerRegistros($sql, $params),
            "total" => $total
        ];
    }

    public static function getUserById(int $id)
    {
        return self::obtenerUnRegistro(
            "SELECT * FROM usuario WHERE usercod = :id",
            ["id" => $id]
        );
    }

    public static function insertUser($email, $name, $status, $type)
    {
        return self::executeNonQuery(
            "INSERT INTO usuario (useremail, username, userest, usertipo)
             VALUES (:e, :n, :s, :t)",
            ["e" => $email, "n" => $name, "s" => $status, "t" => $type]
        );
    }

    public static function updateUser($id, $email, $name, $status, $type)
    {
        return self::executeNonQuery(
            "UPDATE usuario SET useremail=:e, username=:n, userest=:s, usertipo=:t
             WHERE usercod=:id",
            ["id" => $id, "e" => $email, "n" => $name, "s" => $status, "t" => $type]
        );
    }

    public static function deleteUser($id)
    {
        return self::executeNonQuery(
            "DELETE FROM usuario WHERE usercod=:id",
            ["id" => $id]
        );
    }
}