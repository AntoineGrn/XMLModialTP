<?php //Test XMLReader

// Création du parser
$reader = new XMLReader();
$writer = new XMLWriter();
$writer->openMemory();

// Ouverture du flux
$reader->open("mondial.xml");
//$reader->setParserProperty(XMLReader::VALIDATE, true);

$writer->setIndent(true);
$writer->startDocument("1.0", "iso-8859-1");
$writer->writeDTD("liste-pays",null,"liste-pays.dtd");
$writer->startElement("liste-pays");

// Parcours de l'arbre
while($reader->read()) {
    if (($reader->nodeType === XMLReader::ELEMENT) && ($reader->name === 'livre')) {// <livre ..>
        $annee = $reader->getAttribute('annee');
    } else if (($reader->nodeType === XMLReader::ELEMENT) && ($reader->name === 'titre')) {// <titre>
        $titre = $reader->readString();
    } else if (($reader->nodeType === XMLReader::END_ELEMENT) && ($reader->name === 'livre')) {// </livre>
        if ($annee >= 1960) {
            $writer->startElement('livre');
            $writer->writeAttribute('annee',$annee);
            $writer->writeAttribute('titre',$titre);
            $writer->endElement();
        }
    }
}

// Fermeture du flux
$reader->close();
$writer->endElement();
$writer->endDocument();

echo $writer->outputMemory()
?>
