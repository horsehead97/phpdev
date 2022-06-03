<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class GetProductModel extends Database
{
    public function getProduct($upc)
    {
        return $this->select("SELECT name, cost, price, exp FROM products WHERE upc = ?", ["i", $upc]);
    }
}