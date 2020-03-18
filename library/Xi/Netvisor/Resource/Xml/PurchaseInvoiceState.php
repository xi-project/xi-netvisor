<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\Root;

class PurchaseInvoiceState extends Root
{
    public const STATUS_APPROVED = 'approved';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_OPEN_REJECTED = 'contentsupervisorrejected';
    public const STATUS_ACCEPTED_REJECTED  = 'acceptorrejected';

    private $purchaseinvoicenetvisorkey;
    private $status;
    private $isreadyforaccounting;

    /**
     * @param int $netvisorId
     * @param string $status
     * @param bool $isReadyForAccounting
     */
    public function __construct($netvisorId, $status, $isReadyForAccounting)
    {
        $this->purchaseinvoicenetvisorkey = $netvisorId;
        $this->status = $status;
        $this->isreadyforaccounting = (int) $isReadyForAccounting;
    }

    public function getDtdPath()
    {
        return $this->getDtdFile('purchaseinvoicepostingdata.dtd');
    }

    protected function getXmlName()
    {
        return 'purchaseinvoicepostingdata';
    }
}
