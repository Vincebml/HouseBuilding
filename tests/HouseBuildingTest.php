<?php

namespace HouseBuilding;

class HouseBuildingTest extends \PHPUnit_Framework_TestCase
{
    private $houseBuilding;

    protected function setUp()
    {
        $this->houseBuilding = new HouseBuilding();
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage Area must contain between 1 and 50 elements, inclusive.
     */
    public function testMinAreaSizeException()
    {
        $this->houseBuilding->getMinimum([]);
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage Area must contain between 1 and 50 elements, inclusive.
     */
    public function testMaxAreaSizeException()
    {
        $this->houseBuilding->getMinimum(range(0, 55));
    }

    /**
     * @param array $area
     * @expectedException     \Exception
     * @expectedExceptionMessage Each element of area must be a string.
     * @dataProvider typeProvider
     */
    public function testElementType($area)
    {
        $this->houseBuilding->getMinimum($area);
    }

    public function typeProvider()
    {
        return [
            [[40, 40]],
            [[12.3, 40.6]],
            [[new \stdClass(), new \stdClass()]],
            [[true, false]],
            [[['array'], ['array']]],
            [[null, null]],
            [[imagecreate(110, 20), imagecreate(110, 20)]] //resource type
        ];
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage Elements must contain between 1 and 50 characters, inclusive.
     */
    public function testMinElementSizeException()
    {
        $this->houseBuilding->getMinimum(['', '10']);
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage Elements must contain between 1 and 50 characters, inclusive.
     */
    public function testMaxElementSizeException()
    {
        $this->houseBuilding->getMinimum([str_repeat('5', 51)]);
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage All elements of area must be of the same length.
     */
    public function testElementsSameSizeException()
    {
        $this->houseBuilding->getMinimum(['12', '123']);
    }

    /**
     * @param array $area
     * @expectedException     \Exception
     * @expectedExceptionMessage Each element of area must contain digits ('0'-'9') only.
     * @dataProvider contentProvider
     */
    public function testElementsContentException($area)
    {
        $this->houseBuilding->getMinimum($area);
    }

    public function contentProvider()
    {
        return [
            [
                ['4012', 'TeXt'],
                ['401.2', '12345']
            ],
        ];
    }

    /**
     * @param array $area
     * @param int $expected
     * @dataProvider areaProvider
     */
    public function testEffortValues($area, $expected)
    {
        $this->assertSame(
            $expected,
            $this->houseBuilding->getMinimum($area)
        );
    }

    public function areaProvider()
    {
        return [
            [["009"], 8],
            [["10", "31"], 2],
            [["54454", "61551"], 7],
            [["989"], 0],
            [["90"], 8],
            [["5781252", "2471255", "0000291", "1212489"], 53]
        ];
    }
}
