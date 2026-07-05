<?php

namespace Controllers;

use \Dao\Products\Products as ProductsDao;
use \Dao\Users\Users as UsersDao;
use \Dao\Roles\Roles as RolesDao;
use \Dao\Functions\Functions as FunctionsDao;
use \Views\Renderer as Renderer;
use \Utilities\Site as Site;
use \Dao\Generator\Generator as GeneratorDao;

class HomeController extends PublicController
{
    public function run(): void
    {
        Site::addLink("public/css/products.css");
        $viewData = [];

        $viewData["productsOnSale"] = ProductsDao::getDailyDeals();
        $viewData["productsHighlighted"] = ProductsDao::getFeaturedProducts();
        $viewData["productsNew"] = ProductsDao::getNewProducts();

        $viewData["users"] = UsersDao::getUsers("", "", "", false, 0, 5);
        $viewData["roles"] = RolesDao::getRoles("", "", "", false, 0, 5);
        $viewData["functions"] = FunctionsDao::getFunctions("", "", "", false, 0, 5);

        $viewData["generatorTables"] = GeneratorDao::getTableStructure("usuario");

        Renderer::render("home", $viewData);
    }
}