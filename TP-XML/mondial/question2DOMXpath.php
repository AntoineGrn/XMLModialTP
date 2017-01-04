<?php
    header("Content-type: application/xml");
    $document_xml = new DOMDocument(); // Instanciation de la classe DOMDocument : création d'un nouvel objet
    $document_xml->validateOnParse = true; //on valide le doc XML par rapport à la DTD lors du chargement
    $document_xml->preserveWhiteSpace=false; //on ne prend pas en compte les espaces et l'indentation
    $document_xml->load("mondial.xml"); // Chargement de mondial.xml

    $xpath = new DOMXpath($document_xml);
    $query = "/mondial/country[./encompassed/@continent = 'asia' and ./encompassed/@percentage < 100]";
    $resultDom = new DOMDocument();
    $listePays = $resultDom->createElement("liste-pays");

    $listePaysFiltres = $xpath->query($query);

    foreach ($listePaysFiltres as $elem) {
        $nomPays = $xpath->query('name', $elem)->item(0)->nodeValue;
        $codeCapitalePays = $xpath->query("/mondial/country[./name = '".$nomPays."']/@capital")->item(0)->nodeValue;
        $capitalePays = $xpath->query("/mondial/country[./name = '".$nomPays."']/province/city[@id = '".$codeCapitalePays."']/name")->item(0)->nodeValue;
        $proportionAsie = $xpath->query("/mondial/country[./name = '".$nomPays."']/encompassed[@continent = 'asia']/@percentage")->item(0)->nodeValue;
        $proportionAutre = 100 - $proportionAsie;

        /*
         * création de l'élément pays
         */
        $pays = $resultDom->createElement('pays');

        /*
         * Ajout des valeurs des attributs à la balise pays
         */
        $pays->setAttribute('nom',$nomPays);
        $pays->setAttribute('capitale', $capitalePays);
        $pays->setAttribute('proportion-asie', $proportionAsie);
        $pays->setAttribute('proportion-autres', $proportionAutre);

        $listePays -> appendChild($pays);
    }
    $resultDom->appendChild($listePays);
    echo $resultDom->saveXML();
?>