<?php

namespace Dao\{{MODULE}};

use Dao\Table;

class {{CLASS}} extends Table
{
    public static function getAll()
    {
        $sqlstr = "SELECT * FROM {{TABLE}}";
        return self::obtenerRegistros($sqlstr, []);
    }
}