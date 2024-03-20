<?php

require __DIR__ . '/vendor/autoload.php';
use App\Command\ETLCommand;


// Parse command-line arguments
$startDate = isset($argv[1]) ? $argv[1] : null;
$endDate = isset($argv[2]) ? $argv[2] : null;

// Create instance of ETLCommand and execute it
$etlCommand = new ETLCommand();
$etlCommand->execute($startDate, $endDate);

?>