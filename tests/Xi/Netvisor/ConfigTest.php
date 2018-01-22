<?php

namespace Xi\Netvisor\Component;

use Xi\Netvisor\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getsGivenParams()
    {
        $c = new Config(
            true,
            'host',
            'sender',
            'customerId',
            'partnerId',
            'language',
            'organizationId',
            'userKey',
            'partnerKey'
        );

        $this->assertTrue($c->isEnabled());
        $this->assertEquals($c->getHost(), 'host');
        $this->assertEquals($c->getSender(), 'sender');
        $this->assertEquals($c->getCustomerId(), 'customerId');
        $this->assertEquals($c->getPartnerId(), 'partnerId');
        $this->assertEquals($c->getLanguage(), 'language');
        $this->assertEquals($c->getOrganizationId(), 'organizationId');
        $this->assertEquals($c->getUserKey(), 'userKey');
        $this->assertEquals($c->getPartnerKey(), 'partnerKey');
        
        $c->setLanguage('orcish');
        $this->assertEquals($c->getLanguage(), 'orcish');
    }
    
}
