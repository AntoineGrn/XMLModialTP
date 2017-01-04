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
    }

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
        $this->texte = "";
        if ($nom == 'country') {
            $this->country = true;
            $this->result = "<pays ";
        } else if ($nom == 'city' && $attr['is_country_cap'] == 'yes') {;
            $this->city = true;
        } else if ($nom == 'encompassed' && $attr['continent'] == "asia" && $attr['percentage'] < 100) {
            $this->pays = true;
            $this->result .= "proportion-asie='".$attr['percentage']."' ";
            $percent = 100 - $attr['percentage'];
            $this->result .= "proportion-autre='".$percent."' ";
        }
    }

    function endElement($nom){
        if ($nom == 'country') {
            $this->result .= "/>";
            $this->country = false;
            if($this->pays) {
                echo $this->result."\n";
            }
            $this->pays = false;
            $this->result = "";
        } else if ($nom == 'name') {
            if($this->country) {
                $this->result .= "nom='".$this->texte."' ";
                $this->country = false;
            } else if($this->city) {
                $this->result .= "capitale='".$this->texte."'";
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
