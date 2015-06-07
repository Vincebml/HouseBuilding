<?php

namespace HouseBuilding;

class HouseBuilding
{
    /**
     * Return the minimum total effort needed to obtain a leveled area.
     * @param array $area
     * @return int Minimum total effort
     * @throws \Exception
     */
    public function getMinimum(array $area)
    {
        $linearArea = $this
                        ->checkAreaSize($area)
                        ->linearizeArea($area);

        return $this->calculateMinimumEffort($linearArea);
    }

    /**
     * Validate the area's size.
     * @param array $area
     * @return $this
     * @throws \Exception
     */
    private function checkAreaSize($area)
    {
        $areaSize = count($area);
        if ($areaSize < 1 || $areaSize > 50) {
            throw new \Exception("Area must contain between 1 and 50 elements, inclusive.");
        }

        return $this;
    }

    /**
     * Puts all the elements in a single large array and check elements' validity.
     * @param array $area
     * @return array $linearArea
     * @throws \Exception
     */
    private function linearizeArea(array $area)
    {
        $linearArea = [];
        $referenceSize = $this->getElementSize($area[0]);

        foreach ($area as $element) {
            $elementSize = $this->getElementSize($element);

            $this->checkElementSizeAgainstReference($elementSize, $referenceSize)
                 ->checkElementContent($element);

            $linearArea = array_merge(
                $linearArea,
                str_split($element)
            );
        }

        return $linearArea;
    }

    /**
     * Get and validate the element's size.
     * @param string $element
     * @return int $elementSize
     * @throws \Exception
     */
    private function getElementSize($element)
    {
        $this->checkElementType($element);

        $elementSize = strlen($element);
        if ($elementSize < 1 || $elementSize > 50) {
            throw new \Exception("Elements must contain between 1 and 50 characters, inclusive.");
        }

        return $elementSize;
    }

    /**
     * Check if each element of area is a string, as stated in the exercise.
     * @param string $element
     * @throws \Exception
     */
    private function checkElementType($element)
    {
        if (! is_string($element)) {
            throw new \Exception("Each element of area must be a string.");
        }
    }

    /**
     * Check if all elements of area have the same size.
     * @param int $elementSize
     * @param int $referenceSize
     * @return $this
     * @throws \Exception
     */
    private function checkElementSizeAgainstReference($elementSize, $referenceSize)
    {
        if ($elementSize !== $referenceSize) {
            throw new \Exception("All elements of area must be of the same length.");
        }

        return $this;
    }

    /**
     * Check if each element of area contains digits ('0'-'9') only.
     * @param string $element
     * @return $this
     * @throws \Exception
     */
    private function checkElementContent($element)
    {
        if (! ctype_digit($element)) {
            throw new \Exception("Each element of area must contain digits ('0'-'9') only.");
        }

        return $this;
    }

    /**
     * Calculate the minimum total effort you need to put to obtain a leveled area.
     * @param array $linearArea
     * @return int $minimumEffort
     */
    private function calculateMinimumEffort(array $linearArea)
    {
        $minimumEffort = PHP_INT_MAX;

        //Loop and calculate effort for each couple of digits (0 and 1, then 1 and 2, etc.).
        for ($digit = 0; $digit < 10; $digit++) {
            $currentEffort = 0;
            foreach ($linearArea as $squareMeter) {
                $gap = $squareMeter - $digit;

                /**
                 * If $gap = 0 or 1 : leveled, nothing to do.
                 * If $gap > 1 : $squareMeter is closer to $digit+1, so we add $gap-1 to $currentEffort
                 * to level $squareMeter to $digit+1.
                 * If $gap < 0 : $squareMeter is closer to $digit, so we add $gap to $currentEffort
                 * to level $squareMeter to $digit.
                 */
                if ($gap > 1) {
                    $currentEffort += $gap - 1;
                } elseif ($gap < 0) {
                    $currentEffort += abs($gap);
                }
            }

            if ($currentEffort < $minimumEffort) {
                $minimumEffort = $currentEffort;
            }
        }

        return $minimumEffort;
    }
}
