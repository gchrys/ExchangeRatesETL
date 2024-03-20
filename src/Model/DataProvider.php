<?php

namespace App\Model;

abstract class DataProvider{
    
    abstract function fetchData($startDate,$endDate);
    abstract protected function fetchDataForDate($date);
}

?>