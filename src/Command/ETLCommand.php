<?php


namespace App\Command;

use App\Model\OERDataProvider;
use App\Model\CurrencyDataTransformer;
use App\Model\DatabaseDataLoader;
use App\Database\DatabaseConnection;
use Dotenv;

class ETLCommand{
    public function execute($startDate = null, $endDate = null)
    {
        // Load environment variables
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__. '/../../');
        $dotenv->load();

        // Get API key and DB credentials from environment variable
        $apiKey = $_ENV['OPENEXCHANGERATES_API_KEY'];
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        // Create instances of data provider, transformer, and loader
        $dataProvider = new OERDataProvider($apiKey);
        $dataTransformer = new CurrencyDataTransformer();
        // Establish database connection
        $pdo = DatabaseConnection::connect($host, $dbname, $username, $password);

        $dataLoader = new DatabaseDataLoader($pdo);

        try {
            // Fetch data from the data provider
            $data = $dataProvider->fetchData($startDate, $endDate);

            // Transform the fetched data
            $transformedData = $dataTransformer->transformData($data);

            // Load the transformed data into the database
            $dataLoader->loadIntoDatabase($transformedData);

            echo "Data fetched, transformed, and loaded successfully!\n";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}