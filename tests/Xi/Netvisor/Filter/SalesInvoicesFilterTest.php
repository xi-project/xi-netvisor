<?php

namespace Xi\Netvisor\Filter;

use Xi\Netvisor\Filter\SalesInvoicesFilter;
use PHPUnit\Framework\TestCase;

class SalesInvoicesFilterTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testReturnsFilterArray(bool $setId, bool $setInvoiceAboveId, int $count)
    {
        $datetime = new \DateTime('2020-02-02');
        $id = 1;

        $filter = new SalesInvoicesFilter();

        if ($setId) {
            $filter->setModifiedAfterDate($datetime);
        }

        if ($setInvoiceAboveId) {
            $filter->setGreaterThanId($id);
        }   

        $filters = $filter->getFilterArray();
        $this->assertCount($count, $filters);
    }

    public function provider()
    {
        return [
            [
                'setId' => true,
                'setInvoicesAboveId' => true,
                'count' => 2,
            ],
            [
                'setId' => false,
                'setInvoicesAboveId' => true,
                'count' => 1,
            ],
            [
                'setId' => true,
                'setInvoicesAboveId' => false,
                'count' => 1,
            ],
            [
                'setId' => false,
                'setInvoicesAboveId' => false,
                'count' => 0,
            ],
        ];
    }

    
    
}
