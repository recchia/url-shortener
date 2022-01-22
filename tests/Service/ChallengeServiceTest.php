<?php

namespace App\Tests\Service;

use App\Service\ChallengeService;
use PHPUnit\Framework\TestCase;

class ChallengeServiceTest extends TestCase
{
    public function testValidateUPC(): void
    {
        $service = new ChallengeService();

        $this->assertTrue($service->validateUPC('012345678905'));
        $this->assertFalse($service->validateUPC('01234567a905'));
        $this->assertFalse($service->validateUPC('036000241457'));
        $this->assertFalse($service->validateUPC('01'));
        $this->assertTrue($service->validateUPC('010101010105'));
    }

    public function testOrderArray(): void
    {
        $originalArray = [
            ['Milk', 1.25, 2],
            ['Eggs', 4.99, 1],
            ['Granulated sugar', 1.25, 1],
            ['Broccoli', 2.34, 3],
            ['Chocolate bar', 1.25, 5],
            ['Organic All-purpose flour', 4.99, 2],
        ];

        $orderedArray = [
            ['Organic All-purpose flour', 4.99, 2],
            ['Eggs', 4.99, 1],
            ['Broccoli', 2.34, 3],
            ['Chocolate bar', 1.25, 5],
            ['Milk', 1.25, 2],
            ['Granulated sugar', 1.25, 1],
        ];

        $service = new ChallengeService();

        $result = $service->orderProducts($originalArray);

        $this->assertIsArray($result);
        $this->assertEquals($orderedArray, $result);
    }
}
