<?php //Test XMLReader

// Création du parser
$reader = new XMLReader();
$writer = new XMLWriter();
$writer->openMemory();

// Ouverture du flux
$reader->open("mondial.xml");
$city = false;
$country = false;
$pays = false;
//$reader->setParserProperty(XMLReader::VALIDATE, true);

$writer->setIndent(true);
$writer->startDocument("1.0", "iso-8859-1");
$writer->writeDTD("liste-pays",null,"liste-pays.dtd");
$writer->startElement("liste-pays");

// Parcours de l'arbre
while($reader->read()) {
    if (($reader->nodeType === XMLReader::ELEMENT) && ($reader->name === 'country')) {
        $country = true;
    } else if (($reader->nodeType === XMLReader::ELEMENT) && ($reader->name === 'city' && $reader->getAttribute('is_country_cap') == 'yes')) {
        $city = true;
    } else if (($reader->nodeType === XMLReader::ELEMENT) && ($reader->name === 'encompassed' && $reader->getAttribute('continent') == "asia" && $reader->getAttribute('percentage') < 100)) {
        $pays = true;
        $asiaPercentage = $reader->getAttribute('percentage');
        $autre = 100 - $asiaPercentage;
    } else if (($reader->nodeType === XMLReader::ELEMENT) && ($reader->name === 'name')) {
        if($country) {
            $nomCountry = $reader->readString();
            $country = false;
        } else if($city) {
            $capitalName = $reader->readString();
            $city = false;
        }
    } else if (($reader->nodeType === XMLReader::END_ELEMENT) && ($reader->name === 'country')) {
        if ($pays) {
            $writer->startElement('pays');
            $writer->writeAttribute('nom',$nomCountry);
            $writer->writeAttribute('capitale',$capitalName);
            $writer->writeAttribute('proportion-asia',$asiaPercentage);
            $writer->writeAttribute('proportion-autres',$autre);
            $writer->endElement();
        }
        $country = false;
        $city = false;
        $pays = false;
    }
}

// Fermeture du flux
$reader->close();
$writer->endElement();
$writer->endDocument();

echo $writer->outputMemory()
?>
