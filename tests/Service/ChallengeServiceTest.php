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
}
