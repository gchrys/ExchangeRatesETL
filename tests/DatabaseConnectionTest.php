<?php


namespace Tests\Database;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use App\Database\DatabaseConnection;
use Dotenv\Dotenv;


#[CoversClass(DatabaseConnection::class)]
class DatabaseConnectionTest extends TestCase
{
    protected function setUp(): void
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();
    }

    public function testConnectReturnsPDOInstance()
    {
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        $pdo = DatabaseConnection::connect($host, $dbname, $username, $password);
        $this->assertInstanceOf(\PDO::class, $pdo);
    }

    public function testConnectSetsPDODefaultAttributes()
    {
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        $pdo = DatabaseConnection::connect($host, $dbname, $username, $password);
        $this->assertEquals(\PDO::ERRMODE_EXCEPTION, $pdo->getAttribute(\PDO::ATTR_ERRMODE));
        $this->assertEquals(\PDO::FETCH_ASSOC, $pdo->getAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE));
    }

    public function testConnectThrowsExceptionOnFailure()
    {
        // Set invalid environment variables
        $host = 'invalid_host';
        $dbname = 'non_existing_db';
        $username = 'test_user';
        $password = 'test_password';
    

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to connect to the database');

        DatabaseConnection::connect($host, $dbname, $username, $password);

    }
}
?>