<?php
namespace Metaregistrar\EPP;


class atEppInfoDomainResponse extends eppInfoDomainResponse
{
    public function getKeydata() {
        // Check if dnssec is enabled on this interface

        if ($this->findNamespace('secDNS')) {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:extension/secDNS:infData/*');
            $keys = array();

            if (count($result) > 0) {
                foreach ($result as $keydata) {
                    /* @var $keydata \DOMElement */
                    $secdns = new eppSecdns();
                    $secdns->setKeytag($keydata->getElementsByTagName('keyTag')->item(0)->nodeValue);
                    $secdns->setAlgorithm($keydata->getElementsByTagName('alg')->item(0)->nodeValue);
                    $secdns->setDigestType($keydata->getElementsByTagName('digestType')->item(0)->nodeValue);
                    $secdns->setDigest($keydata->getElementsByTagName('digest')->item(0)->nodeValue);
                    $keys[] = $secdns;
                }
            }
            return $keys;
        }
        return null;
    }

    public function getValidationStatus()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace ( "at-ext-verification" , atEppConstants::namespaceAtExtVerification );

        $result = $xpath->query('/epp:epp/epp:response/epp:extension/at-ext-verification:infData/at-ext-verification:status/@s');
        if (!is_null($result) && $result->length > 0) {
            return $result->item(0)->nodeValue;
        }

        return null;
    }

}