<?php
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use App\Model\DatabaseDataLoader;
use App\Model\DataLoader;
use PDO;

#[CoversClass(DatabaseDataLoader::class)]
#[UsesClass(DataLoader::class)]
class DatabaseDataLoaderTest extends TestCase
{
    private $pdoMock;

    protected function setUp(): void
    {
        // Create a PDO mock instance
        $this->pdoMock = $this->createMock(PDO::class);
    }

    public function testLoadIntoDatabase()
    {
        // Mock data to be loaded into the database
        $data = [
            '2024-03-01' => ['USD' => 1.23, 'EUR' => 0.92205]
        ];

        // Mock the PDO prepare method
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('bindValue')
            ->willReturn(true); // Return true for all calls to bindValue
        $stmtMock->expects($this->exactly(2)) // Expecting two executions for each currency rate
            ->method('execute');

        // Mock the PDO prepare method to return the prepared statement mock
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);

        $databaseDataLoader = new DatabaseDataLoader($this->pdoMock);

        $databaseDataLoader->loadIntoDatabase($data);
    }
}
?>