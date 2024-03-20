<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use App\Model\OERDataProvider;
use App\Model\DataProvider;
use DateTimeImmutable;
use Dotenv\Dotenv;


#[CoversClass(OERDataProvider::class)]
#[UsesClass(DataProvider::class)]
class OERDataProviderTest extends TestCase
{
    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();
    }
    public function testFetchDataWithDefaultDates()
    {
        $mockApiKey = $_ENV['OPENEXCHANGERATES_API_KEY'];
        $currentDate = (new DateTimeImmutable())->format('Y-m-d');
        $mockResponse = [
            $currentDate => ['USD' => 1, 'EUR' => 0.92205]
        ];


        $mockedDataProvider = $this->getMockBuilder(OERDataProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['fetchDataForDate'])
            ->getMock();

        $mockedDataProvider->expects($this->any())
            ->method('fetchDataForDate')
            ->willReturn($mockResponse);

        $dataProvider = new OERDataProvider($mockApiKey);
        $data = $dataProvider->fetchData();

        $this->assertArrayHasKey($currentDate, $data);
    
    }

    public function testFetchDataWithCustomDates()
    {
        $mockApiKey = $_ENV['OPENEXCHANGERATES_API_KEY'];
        $mockResponse = [
            '2024-03-01' => ['USD' => 1, 'EUR' => 0.92205]
        ];

        $mockedDataProvider = $this->getMockBuilder(OERDataProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['fetchDataForDate'])
            ->getMock();

        $mockedDataProvider->expects($this->any())
            ->method('fetchDataForDate')
            ->willReturn($mockResponse);

        $dataProvider = new OERDataProvider($mockApiKey);
        $startDate = '2024-03-01';
        $endDate = '2024-03-01';
        $data = $dataProvider->fetchData($startDate, $endDate);

        $expectedData = ['USD' => 1, 'EUR' => 0.92205];
        $this->assertEquals($expectedData, array_intersect_key($data['2024-03-01'], $expectedData));
    }

    public function testFetchDataWithInvalidDates()
    {
        $mockApiKey = $_ENV['OPENEXCHANGERATES_API_KEY'];
        $dataProvider = new OERDataProvider($mockApiKey);

        $this->expectException(Exception::class);
        $dataProvider->fetchData('2024-03-02', '2024-03-01');
    }

    public function testFetchDataForDateWithReflection()
    {
        $mockApiKey = $_ENV['OPENEXCHANGERATES_API_KEY'];
        $mockResponse = ['USD' => 1, 'EUR' => 0.92205];

        // Create a new instance of OERDataProvider
        $dataProvider = new OERDataProvider($mockApiKey);

        // Use ReflectionClass to access the protected fetchDataForDate method
        $reflectionClass = new ReflectionClass(OERDataProvider::class);
        $fetchDataForDateMethod = $reflectionClass->getMethod('fetchDataForDate');
        $fetchDataForDateMethod->setAccessible(true);

        // Call the protected method with the desired date
        $actualResponse = $fetchDataForDateMethod->invokeArgs($dataProvider, ['2024-03-01']);

        // Assert the response matches the expected response
        $this->assertEquals($mockResponse, array_intersect_key($actualResponse, $mockResponse));
    }

    public function testFetchDataForDateInvalidDate()
    {
        $mockApiKey = $_ENV['OPENEXCHANGERATES_API_KEY'];

        // Create a new instance of OERDataProvider
        $dataProvider = new OERDataProvider($mockApiKey);

        // Use ReflectionClass to access the protected fetchDataForDate method
        $reflectionClass = new ReflectionClass(OERDataProvider::class);
        $fetchDataForDateMethod = $reflectionClass->getMethod('fetchDataForDate');
        $fetchDataForDateMethod->setAccessible(true);

        // Expect the response to throw Exception
        $this->expectException(Exception::class);
        $fetchDataForDateMethod->invokeArgs($dataProvider, ['Invalid_Date']);
    }
}