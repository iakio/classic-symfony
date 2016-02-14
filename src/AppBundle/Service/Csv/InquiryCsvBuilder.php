<?php

namespace AppBundle\Service\Csv;


use League\Csv\Writer;

class InquiryCsvBuilder
{
    private $encoding;
    private $inquiryRepository;

    public function __construct($encoding, $inquiryRepository)
    {

        $this->encoding = $encoding;
        $this->inquiryRepository = $inquiryRepository;
    }

    public function build($keyword)
    {
        $inquiryList = $this->inquiryRepository->findAllByKeyword($keyword);

        /** @var Writer $writer */
        $writer = Writer::createFromString("");
        $writer->setNewline("\r\n");

        foreach ($inquiryList as $inquiry) {
            $writer->insertOne([
                $inquiry->getId(),
                $inquiry->getName(),
                $inquiry->getEmail()
            ]);
        }
        return mb_convert_encoding((string) $writer, $this->encoding, 'UTF-8');
    }
}
