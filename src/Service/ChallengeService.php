<?php

namespace App\Service;

class ChallengeService
{
    private array $products = [
        ['Milk', '1.25', 2],
        ['Eggs', '4.99', 1],
        ['Granulated sugar', '1.25', 1],
        ['Broccoli', '2.34', 3],
        ['Chocolate bar', '1.25', 5],
        ['Organic All-purpose flour', '4.99', 2],
    ];

    public function orderProducts(): array
    {
        $auxArray = [];
        $lenght = count($this->products);

        for ($i = 0; $i < $lenght; $i++) {
            if ($this->products[$i][2] > $this->products[$i+1]) {
                $auxArray = $this->products[$i];
            }
        }
    }

    public function validateUPC(int $upcNumber): bool
    {

        $numberOdd = 0;
        $numberEven = 0;
        $totalDigits = strlen((string)$upcNumber);

        for ($i = 1; $i < $totalDigits - 1; $i++) {
            $type = $i % 2 === 0 ? 'even' : 'odd';
            $digit = substr((string)$upcNumber, $i - 1, 1);
            if (! is_numeric($digit)) {
                return false;
            }

            if ($type === 'odd') {
                $numberOdd += (int)$digit;
            } elseif ($type === 'even') {
                $numberEven += (int)$digit;
            }
        }

        $addResult = $numberOdd * 3;
        $addResult += $numberEven;

        $remain = $addResult % 10;

        $result = $remain === 0 ? 0 : 10 - $remain;

        return substr((string)$upcNumber, $totalDigits - 1, 1) === (string)$result;
    }

}

$service = new ChallengeService();
/*$service->validateUPC(012345678905);
$service->validateUPC(01234567a905);
$service->validateUPC(036000241457);
$service->validateUPC(01);*/
