<?php
include_once('Sax4PHP.php');
header("Content-type: application/xml");

class Question2SAX extends DefaultHandler{
private $capitale = '';
private $name = '';
private $asia = 0;
private $autre = 0;
private $capitaleName = '';
private $cityBool = false;
    //initialisation du document
    function startDocument() {
        //entete du document xml resultant
        echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
        echo "<!DOCTYPE liste-pays SYSTEM 'mondial.dtd'>\n";
        echo "<liste-pays>\n";
    }

    //fin du document
    function endDocument() {
        echo "</liste-pays>\n";
    }

    //lecture d'un noeud texte
    function characters($txt) {
        $txt = trim($txt);
        if (!(empty($txt))) $this->texte .= $txt;
    }

    function startElement($nom, $attr){
        if ($nom == 'country') {
            $this->capitale = $attr['capital'];
        } else if ($nom == 'city' && $attr['id'] == $this->capitale) {
            $this->$cityBool = false;
        } else if ($nom == 'name' && $this->cityBool) {
            $this->capitaleName = $this->texte;
            $this->cityBool = false;
        } else if ($nom == 'encompassed' && $attr['percentage'] < 100 && $attr['continent'] == 'asia') {
            $this->asia = $attr['percentage'];
            $this->autre = 100 - $this->asia;
        }
        echo '<pays nom="'.$this->name.'" capitale="'.$this->capitale.'" proportion-asie="'.$this->asia.'" proportion-autres="'.$this->autre.'" />';
    }

    function endElement(){
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
