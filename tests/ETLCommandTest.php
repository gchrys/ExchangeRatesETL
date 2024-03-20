<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use App\Command\ETLCommand;
use App\Model\OERDataProvider;
use App\Model\CurrencyDataTransformer;
use App\Model\DatabaseDataLoader;
use App\Database\DatabaseConnection;
use Dotenv\Dotenv;

#[CoversClass(ETLCommand::class)]
#[UsesClass(OERDataProvider::class)]
#[UsesClass(CurrencyDataTransformer::class)]
#[UsesClass(DatabaseDataLoader::class)]
#[UsesClass(DatabaseConnection::class)]
class ETLCommandTest extends TestCase
{
    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();
    }
    public function testETLCommand()
    {
        // Get API key and DB credentials from environment variable
        $apiKey = $_ENV['OPENEXCHANGERATES_API_KEY'];
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        $pdo = DatabaseConnection::connect($host, $dbname, $username, $password);

        // Create instances of data provider, transformer, and loader
        $dataProvider = new OERDataProvider($apiKey);
        $dataTransformer = new CurrencyDataTransformer();
        $dataLoader = new DatabaseDataLoader($pdo);

        // Create an instance of ETLCommand with real dependencies
        $etlCommand = new ETLCommand($dataProvider, $dataTransformer, $dataLoader);

        $startDate = '2024-03-01';
        $endDate = '2024-03-01';

        // Execute the ETL process
        ob_start(); 
        $etlCommand->execute($startDate,$endDate);
        $output = ob_get_clean(); 

        // Assert that the command output indicates success
        $this->assertStringContainsString('Data fetched, transformed, and loaded successfully!', $output);
    }

    public function testETLCommandHandlesException()
    {
        // Get API key and DB credentials from environment variable
        $apiKey = $_ENV['OPENEXCHANGERATES_API_KEY'];
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        $pdo = DatabaseConnection::connect($host, $dbname, $username, $password);

        // Create instances of data provider, transformer, and loader
        $dataProvider = new OERDataProvider($apiKey);
        $dataTransformer = new CurrencyDataTransformer();
        $dataLoader = new DatabaseDataLoader($pdo);

        // Create an instance of ETLCommand with real dependencies
        $etlCommand = new ETLCommand($dataProvider, $dataTransformer, $dataLoader);

        $startDate = '2024-03-02';
        $endDate = '2024-03-01';

        // Execute the ETL process
        ob_start(); 
        $etlCommand->execute($startDate,$endDate);
        $output = ob_get_clean(); 

        // Assert that the command output indicates failure
        $this->assertStringContainsString('Error:', $output);
    }
}

?>