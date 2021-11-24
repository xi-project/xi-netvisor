<?php

namespace Xi\Netvisor\Filter;

class SalesInvoicesFilter
{
    /**
     * @var \DateTime
     */
    private $lastmodifiedstart;

    /**
     * @var int
     */
    private $invoicesabovenetvisorkey;

    public function getFilterArray()
    {
        return array_filter(get_object_vars($this));
    }

    public function setModifiedAfterDate(\DateTime $date)
    {
        $this->lastmodifiedstart = $date->format('Y-m-d');
    }

    public function setGreaterThanId(int $id)
    {
        $this->invoicesabovenetvisorkey = $id;
    }
}
