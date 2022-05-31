<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class TradeHistModel extends Database
{
    public function getTrades($limit)
    {
        return $this->select("SELECT * FROM trades ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }
}