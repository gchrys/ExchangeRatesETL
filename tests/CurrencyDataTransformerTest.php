<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use App\Model\CurrencyDataTransformer;
use App\Model\DataTransformer;


#[CoversClass(CurrencyDataTransformer::class)]
#[UsesClass(DataTransformer::class)]
class CurrencyDataTransformerTest extends TestCase
{
    public function testTransformData()
    {
        $data = [
            '2024-03-01' => ['USD' => 1, 'AED' => 3.67,'EUR' => 0.91],
            '2024-03-02' => ['USD' => 1, 'AED' => 3.55,'EUR' => 0.92],
        ];

        $transformer = new CurrencyDataTransformer();
        $transformedData = $transformer->transformData($data);

        $this->assertIsArray($transformedData);
        $this->assertArrayHasKey('2024-03-01', $transformedData);
        $this->assertArrayHasKey('2024-03-02', $transformedData);
        $this->assertEquals(['USD' => 1.0989010989010988, 'AED' => 4.032967032967033, 'EUR' => 1], $transformedData['2024-03-01']);
        $this->assertEquals(['USD' => 1.0869565217391304, 'AED' => 3.8586956521739126, 'EUR' => 1], $transformedData['2024-03-02']);
    }
}
?>