<?php

namespace App\Service;

class ChallengeService
{
    public function orderProducts(array $array): array
    {
        $prices = array_column($array, 1);
        $quantities = array_column($array, 2);

        array_multisort($prices, SORT_DESC, $quantities, SORT_DESC, $array);

        return $array;
    }

    public function validateUPC(string $upcNumber): bool
    {

        $numberOdd = 0;
        $numberEven = 0;
        $totalDigits = strlen($upcNumber);

        for ($i = 1; $i < $totalDigits - 1; $i++) {
            $type = $i % 2 === 0 ? 'even' : 'odd';
            $digit = substr($upcNumber, $i - 1, 1);
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
