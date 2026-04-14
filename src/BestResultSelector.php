<?php

declare(strict_types=1);

namespace Happenv\AddressSplitter;

use Happenv\AddressSplitter\Dto\ParsedAddress;

final class BestResultSelector
{
    /**
     * @param  list<ParsedAddress>  $results
     */
    public function select(array $results): ?ParsedAddress
    {
        if ($results === []) {
            return null;
        }

        $best = $results[0];
        $bestScore = $this->score($best);

        foreach ($results as $result) {
            $score = $this->score($result);

            if ($score > $bestScore) {
                $best = $result;
                $bestScore = $score;
            }
        }

        return $best;
    }

    private function score(ParsedAddress $result): int
    {
        $score = 0;

        if ($result->street !== '') {
            $score++;
        }

        if ($result->buildingNumber !== null) {
            $score++;
        }

        if ($result->apartmentNumber !== null) {
            $score++;
        }

        return $score;
    }
}
