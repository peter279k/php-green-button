<?php

namespace Lee\Green\Button;

use XMLWriter;
use Exception;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

/**
 * CustomerWriter to generate customer agreement information
 */
class CustomerDataWriter
{
    /**
     * @var string Title
     */
    protected static $title;

    /**
     * @var string The account id
     */
    protected static $accountId;

    /**
     * @var string The published date time
     */
    protected static $publishedDateTime;

    /**
     * @var string The updated date time
     */
    protected static $updatedDateTime;

    /**
     * @var string Meter form number
     */
    protected static $meterFormNumber;

    /**
     * @var string End device Serial Number
     */
    protected static $endDeviceSerialNumber;

    /**
     * @var string The timezone
     */
    protected static $timezone;

    /**
     * Constructor
     *
     * @param array<mixed> $options The customer information options
     */
    public function __construct(array $options)
    {
        $this->validateOptions($options);

        self::$title = $options['title'];
        self::$accountId = $options['account_id'];
        self::$meterFormNumber = $options['meter_form_number'];
        self::$endDeviceSerialNumber = $options['end_device_serial_number'];
        self::$timezone = $options['timezone'] ?? date_default_timezone_get();
    }

    /**
     * Validate specific options
     *
     * @param array<mixed> $options The options for validation
     *
     * @return bool
     * @throws Exception
     */
    public function validateOptions(array $options): bool
    {
        if (array_key_exists('title', $options) === false) {
            throw new Exception('Please fill title key');
        }
        if (array_key_exists('account_id', $options) === false) {
            throw new Exception('Please fill account_id key');
        }
        if (array_key_exists('meter_form_number', $options) === false) {
            throw new Exception('Please fill meter_form_number');
        }
        if (array_key_exists('end_device_serial_number', $options) === false) {
            throw new Exception('Please fill end_device_serial_number');
        }

        return true;
    }

    /**
     * Get published date time
     *
     * @return string
     */
    public function getPublishedDateTime(): string
    {
        if (self::$publishedDateTime === '' || self::$publishedDateTime === null) {
            throw new Exception('Please specify published date time');
        }

        return self::$publishedDateTime;
    }

    /**
     * Get updated date time
     *
     * @return string
     */
    public function getUpdatedDateTime(): string
    {
        if (self::$updatedDateTime === '' || self::$updatedDateTime === null) {
            throw new Exception('Please specify updated date time');
        }

        return self::$updatedDateTime;
    }

    /**
     * Set published date time
     *
     * @param string $dateTimeString The formatted time string (default is system timezone)
     *
     * @return self
     */
    public function setPublishedDateTime(string $dateTimeString = null): self
    {
        self::$publishedDateTime = $dateTimeString ?? Carbon::now(self::$timezone)->format('Y-m-d\TH:i:s\Z');

        return $this;
    }

    /**
     * Set updated date time
     *
     * @param string $dateTimeString The formatted time string (default is system timezone)
     *
     * @return self
     */
    public function setUpdatedDateTime(string $dateTimeString = null): self
    {
        self::$updatedDateTime = $dateTimeString ?? Carbon::now(self::$timezone)->format('Y-m-d\TH:i:s\Z');

        return $this;
    }

    /**
     * Get Feed id with random UUIDv4
     *
     * @return string
     */
    protected function getId(): string
    {
        return 'urn:uuid:' . strtoupper((string)Uuid::uuid4());
    }

    /**
     * Output for Green Button XML file
     *
     * @return string
     */
    public function createCustomerAccountData(): string
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);

        $xmlWriter->startDocument('1.0', 'utf-8');
        $xmlWriter->startElement('feed');
        $xmlWriter->writeAttribute('xmlns', 'http://www.w3.org/2005/Atom');
        $xmlWriter->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        $xmlWriter->writeElement('id', $this->getId());
        $xmlWriter->writeElement('title', self::$title);
        $xmlWriter->writeElement('updated', $this->getUpdatedDateTime());

        $xmlWriter = $this->getCustomerAccountInformation($xmlWriter);

        $xmlWriter = $this->getCustomerAgreementInformation($xmlWriter);

        $xmlWriter = $this->getMeterFormInformation($xmlWriter);

        $xmlWriter->endElement();

        return $xmlWriter->outputMemory();
    }

    /**
     * Get customer account information
     *
     * @param XMLWriter $xmlWriter The XMLWriter class instance
     *
     * @return XMLWriter
     */
    protected function getCustomerAccountInformation(XMLWriter $xmlWriter): XMLWriter
    {
        $xmlWriter->startElement('entry');
        $xmlWriter->writeElement('id', $this->getId());

        $xmlWriter->startElement('link');
        $xmlWriter->writeAttribute(
            'href',
            'DataCustodian/espi/1_1/resource/RetailCustomer/VJEWP31BE/Customer/1/CustomerAccount/1111111'
        );
        $xmlWriter->writeAttribute('rel', 'self');
        $xmlWriter->endElement();

        $xmlWriter->startElement('link');
        $xmlWriter->writeAttribute(
            'href',
            'DataCustodian/espi/1_1/resource/RetailCustomer/VJEWP31BE/Customer/1/CustomerAccount'
        );
        $xmlWriter->writeAttribute('rel', 'up');
        $xmlWriter->endElement();

        $xmlWriter->startElement('link');
        $xmlWriter->writeAttribute(
            'href',
            'DataCustodian/espi/1_1/resource/RetailCustomer/VJEWP31BE/Customer/1/CustomerAccount/1111111/CustomerAgreement'
        );
        $xmlWriter->writeAttribute('rel', 'related');
        $xmlWriter->endElement();

        $xmlWriter->writeElement('title', 'CustomerAccount information');

        $xmlWriter->startElement('content');

        $xmlWriter->startElement('CustomerAccount');
        $xmlWriter->writeAttribute('xmlns', 'http://naesb.org/espi/customer');
        $xmlWriter->writeElement('accountId', self::$accountId);
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->writeElement('published', $this->getPublishedDateTime());
        $xmlWriter->writeElement('updated', $this->getUpdatedDateTime());

        $xmlWriter->endElement();

        return $xmlWriter;
    }

    /**
     * Get customer agreement information
     *
     * @param XMLWriter $xmlWriter The XMLWriter class instance
     *
     * @return XMLWriter
     */
    protected function getCustomerAgreementInformation(XMLWriter $xmlWriter): XMLWriter
    {
        $xmlWriter->startElement('entry');
        $xmlWriter->writeElement('id', $this->getId());

        $xmlWriter->startElement('link');
        $xmlWriter->writeAttribute(
            'href',
            'DataCustodian/espi/1_1/resource/RetailCustomer/VJEWP31BE/Customer/1/CustomerAccount/1111111/CustomerAgreement/NB6WRU'
        );
        $xmlWriter->writeAttribute('rel', 'self');
        $xmlWriter->endElement();

        $xmlWriter->startElement('link');
        $xmlWriter->writeAttribute(
            'href',
            'DataCustodian/espi/1_1/resource/RetailCustomer/VJEWP31BE/Customer/1/CustomerAccount/1111111/CustomerAgreement'
        );
        $xmlWriter->writeAttribute('rel', 'up');
        $xmlWriter->endElement();

        $xmlWriter->writeElement('title', 'CustomerAgreement information');

        $xmlWriter->startElement('content');

        $xmlWriter->startElement('CustomerAgreement');
        $xmlWriter->writeAttribute('xmlns', 'http://naesb.org/espi/customer');
        $xmlWriter->writeElement('agreementId', self::$accountId);
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->writeElement('published', $this->getPublishedDateTime());
        $xmlWriter->writeElement('updated', $this->getUpdatedDateTime());

        $xmlWriter->endElement();

        return $xmlWriter;
    }

    /**
     * Get meter information
     *
     * @param XMLWriter $xmlWriter The XMLWriter class instance
     *
     * @return XMLWriter
     */
    protected function getMeterFormInformation(XMLWriter $xmlWriter): XMLWriter
    {
        $xmlWriter->startElement('entry');
        $xmlWriter->writeElement('id', $this->getId());

        $xmlWriter->startElement('link');
        $xmlWriter->writeAttribute(
            'href',
            'DataCustodian/espi/1_1/resource/RetailCustomer/VJEWP31BE/Customer/1/CustomerAccount/1111111/CustomerAgreement/NB6WRU/Meter/14106263'
        );
        $xmlWriter->writeAttribute('rel', 'self');
        $xmlWriter->endElement();

        $xmlWriter->startElement('link');
        $xmlWriter->writeAttribute(
            'href',
            'DataCustodian/espi/1_1/resource/RetailCustomer/VJEWP31BE/Customer/1/CustomerAccount/1111111/CustomerAgreement/NB6WRU/Meter'
        );
        $xmlWriter->writeAttribute('rel', 'up');
        $xmlWriter->endElement();

        $xmlWriter->startElement('link');
        $xmlWriter->writeAttribute(
            'href',
            'DataCustodian/espi/1_1/resource/RetailCustomer/VJEWP31BE/Customer/1/CustomerAccount/1111111/CustomerAgreement/NB6WRU/Meter/14106263/EndDevice'
        );
        $xmlWriter->writeAttribute('rel', 'related');
        $xmlWriter->endElement();

        $xmlWriter->writeElement('title', 'Meter form information');

        $xmlWriter->startElement('content');

        $xmlWriter->startElement('Meter');
        $xmlWriter->writeAttribute('xmlns', 'http://naesb.org/espi/customer');
        $xmlWriter->writeElement('formNumber', self::$meterFormNumber);
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->writeElement('published', $this->getPublishedDateTime());
        $xmlWriter->writeElement('updated', $this->getUpdatedDateTime());

        $xmlWriter->endElement();

        return $xmlWriter;
    }

    /**
     * Get end device information
     *
     * @param XMLWriter $xmlWriter The XMLWriter class instance
     *
     * @return XMLWriter
     */
    protected function getEndDeviceInformation(XMLWriter $xmlWriter): XMLWriter
    {
        $xmlWriter->startElement('entry');
        $xmlWriter->writeElement('id', $this->getId());

        $xmlWriter->startElement('link');
        $xmlWriter->writeAttribute(
            'href',
            'DataCustodian/espi/1_1/resource/RetailCustomer/VJEWP31BE/Customer/1/CustomerAccount/1111111/CustomerAgreement/NB6WRU/Meter/14106263/EndDevice/14106263'
        );
        $xmlWriter->writeAttribute('rel', 'self');
        $xmlWriter->endElement();

        $xmlWriter->startElement('link');
        $xmlWriter->writeAttribute(
            'href',
            'DataCustodian/espi/1_1/resource/RetailCustomer/VJEWP31BE/Customer/1/CustomerAccount/1111111/CustomerAgreement/NB6WRU/Meter/EndDevice'
        );
        $xmlWriter->writeAttribute('rel', 'up');
        $xmlWriter->endElement();

        $xmlWriter->writeElement('title', 'EndDevice information');

        $xmlWriter->startElement('content');

        $xmlWriter->startElement('EndDevice');
        $xmlWriter->writeAttribute('xmlns', 'http://naesb.org/espi/customer');
        $xmlWriter->writeElement('serialNumber', self::$endDeviceSerialNumber);
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->writeElement('published', $this->getPublishedDateTime());
        $xmlWriter->writeElement('updated', $this->getUpdatedDateTime());

        $xmlWriter->endElement();

        return $xmlWriter;
    }

    /**
     * Generate electric power usage data sets
     *
     * @return XMLWriter
     */
    protected static function generateElectricUsageData(XMLWriter $xmlWriter): XMLWriter
    {
        $xmlWriter->startElement('entry');

        return $xmlWriter;
    }
}
