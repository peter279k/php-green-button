<?php

namespace Lee\Green\Button\Tests;

use PHPUnit\Framework\TestCase;
use Lee\Green\Button\CustomerDataWriter;
use XMLReader;

class CustomerDataWriterTest extends TestCase
{
    /**
     * @var string Date time string
     */
    protected $dateTimeString;

    /**
     * Setup for initializing PHPUnit fixtures
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->dateTimeString = '2020-08-20T07:05:51Z';
    }

    /**
     * createCustomerAccountData test
     */
    public function testCreateCustomerAccountData(): void
    {
        $options = [
            'title' => 'Green Button Customer Feed',
            'account_id' => 'Peter',
            'meter_form_number' => 'TD17234599',
            'end_device_serial_number' => '99123456',
        ];
        $expectedTags = [
            'feed',
            '#text',
            'id',
            '#text',
            '#text',
            'title',
            '#text',
            '#text',
            'updated',
            '#text',
            '#text',
            'entry',
            '#text',
            'id',
            '#text',
            '#text',
            'link',
            '#text',
            'link',
            '#text',
            'link',
            '#text',
            'title',
            '#text',
            '#text',
            'content',
            '#text',
            'CustomerAccount',
            '#text',
            'accountId',
            '#text',
            '#text',
            '#text',
            '#text',
            'published',
            '#text',
            '#text',
            'updated',
            '#text',
            '#text',
            '#text',
            'entry',
            '#text',
            'id',
            '#text',
            '#text',
            'link',
            '#text',
            'link',
            '#text',
            'title',
            '#text',
            '#text',
            'content',
            '#text',
            'CustomerAgreement',
            '#text',
            'agreementId',
            '#text',
            '#text',
            '#text',
            '#text',
            'published',
            '#text',
            '#text',
            'updated',
            '#text',
            '#text',
            '#text',
            'entry',
            '#text',
            'id',
            '#text',
            '#text',
            'link',
            '#text',
            'link',
            '#text',
            'link',
            '#text',
            'title',
            '#text',
            '#text',
            'content',
            '#text',
            'Meter',
            '#text',
            'formNumber',
            '#text',
            '#text',
            '#text',
            '#text',
            'published',
            '#text',
            '#text',
            'updated',
            '#text',
            '#text',
            '#text',
        ];
        $customerDataWriter = new CustomerDataWriter($options);
        $customerDataWriter->setPublishedDateTime($this->dateTimeString);
        $customerDataWriter->setUpdatedDateTime($this->dateTimeString);

        $result = $customerDataWriter->createCustomerAccountData();

        $xmlReader = new XMLReader();
        $xmlReader->xml($result);

        $resultNodes = [];
        while ($xmlReader->read() === true) {
            if ($xmlReader->nodeType == XMLReader::END_ELEMENT) {
                continue;
            }

            $resultNodes[] = $xmlReader->name;
        }

        $xmlReader->close();

        $this->assertSame($expectedTags, $resultNodes);
    }
}
