<?php
include_once('Sax4PHP.php');
header("Content-type: application/xml");

class Question2SAX extends DefaultHandler{
    function __construct() {
        parent::__construct();
        $this->city = false;
        $this->country = false;
        $this->pays = false;

        $this->result = '';
        $this->fileXml = '';
        $this->documentXml = '';
        $this->element = '';
    }

    //initialisation du document
    function startDocument() {
        //entete du document xml resultant
        $this->documentXml = new DOMImplementation;
        $dtd = $this->documentXml->createDocumentType('liste-pays', '', 'liste-pays.dtd');
        $this->fileXml = $this->documentXml->createDocument("", "", $dtd);
        $this->fileXml->validateOnParse = true;
        $this->fileXml->preserveWhiteSpace=false;
        $this->fileXml->formatOutput = true;
        $this->result = $this->fileXml->createElement('liste-pays');
        $this->fileXml->appendChild($this->result);
    }

    //fin du document
    function endDocument() {
        echo $this->fileXml->saveXML();
    }

    //lecture d'un noeud texte
    function characters($txt) {
        $txt = trim($txt);
        if (!(empty($txt))) $this->texte .= $txt;
    }

    function startElement($nom, $attr){
        $this->texte = "";
        if ($nom == 'country') {
            $this->country = true;
            $this->element = $this->fileXml->createElement('pays');
        } else if ($nom == 'city' && $attr['is_country_cap'] == 'yes') {;
            $this->city = true;
        } else if ($nom == 'encompassed' && $attr['continent'] == "asia" && $attr['percentage'] < 100) {
            $this->pays = true;

            $asiaPercentage = $this->fileXml->createAttribute('proportion-asie');
            $asiaPercentage->value = $attr['percentage'];
            $this->element->appendChild($asiaPercentage);

            $otherPercentage = $this->fileXml->createAttribute('proportion-autres');
            $otherPercentage->value = 100 - $attr['percentage'];
            $this->element->appendChild($otherPercentage);
        }
    }

    function endElement($nom){
        if ($nom == 'country') {
            $this->country = false;
            if($this->pays) {
                $this->result->appendChild($this->element);
            }
            $this->pays = false;
        } else if ($nom == 'name') {
            if($this->country) {
                $nomElement = $this->fileXml->createAttribute('nom');
                $nomElement->value = $this->texte;
                $this->element->appendChild($nomElement);
                $this->country = false;
            } else if($this->city) {
                $capitalElement = $this->fileXml->createAttribute('capitale');
                $capitalElement->value = $this->texte;
                $this->element->appendChild($capitalElement);
                $this->city = false;
            }
        }
         else if ($nom == 'city') {
            $this->city = false;
        }
    }
}

$xml = file_get_contents('mondial/mondial.xml');
$sax = new SaxParser(new Question2SAX());
try {
    $sax->parse($xml);
}catch(SAXException $e){
    echo "\n",$e;
}catch(Exception $e) {
    echo "Exception par défaut : ", $e;
}
?>
