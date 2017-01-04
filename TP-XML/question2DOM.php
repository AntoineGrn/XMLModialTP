<?php
header("Content-type: application/xml");
$documentXml = new DOMDocument();
$documentXml->validateOnParse = true;
$documentXml->preserveWhiteSpace=false;
$documentXml->formatOutput = true;
$documentXml->load("mondial.xml"); 

$newDom = new DOMDocument();
$racine = $newDom->createElement('liste-pays');

$listePays = $documentXml->getElementsByTagName("country");

foreach ($listePays as $pays) {
    $countryAsia = false;
    $country_name = '';
    $country_city = '';
    $country_percentage = '';

    foreach ($pays->childNodes as $country) {
        if($country->tagName == 'name') {
            $country_name = $country->nodeValue;
        } elseif ($country->tagName == 'city') {
            if($country->hasAttribute = 'is_country_cap'){
                foreach ($country->childNodes as $proprietes) {
                    if ($proprietes->tagName == 'name') {
                        $country_city = $proprietes->nodeValue;
                    }
                }
            }
        } elseif ($country->tagName == 'encompassed') {
            if ($country->getAttribute('continent') == "asia" && $country->getAttribute('percentage') != "100") {
                $countryAsia = true;
                $country_percentage = $country->getAttribute('percentage');
            }
        }
    }
    if ($countryAsia) {
        $pays = $newDom->createElement('pays');
        $pays->setAttribute('nom', $country_name);
        $pays->setAttribute('capitale', $country_city);
        $pays->setAttribute('proportion-asie', $country_percentage);
        $pays->setAttribute('proportion-autre', 100-$country_percentage);

        $racine->appendChild($pays);
    }
}
$newDom->appendChild($racine);
echo $newDom->saveXML();
?>
