<?php

namespace App\Model;

abstract class DataLoader {
    abstract public function loadIntoDatabase($data);
}

?>