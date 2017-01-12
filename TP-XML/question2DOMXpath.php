<?php
    header("Content-type: application/xml");
    $document_xml = new DOMDocument(); // création d'un nouveau document
    $document_xml->validateOnParse = true; //on valide le doc XML par rapport à la DTD lors du chargement
    $document_xml->preserveWhiteSpace=false; //on ne prend pas en compte les espaces et l'indentation
    $document_xml->load("mondial.xml"); // chargement de mondial.xml

    $xpath = new DOMXpath($document_xml);

    /*
     * Initialisation du nouveau document à retourner
     */
    $resultDom = new DOMDocument();
    /*
     * Création de l'élément racine au document
     */
    $listePays = $resultDom->createElement("liste-pays");

    /*
     * Récupération de la liste (NodeList) des pays ayant un pourcentage d'asiatique < 100
     */
    $query = "/mondial/country[./encompassed/@continent = 'asia' and ./encompassed/@percentage < 100]";
    $listePaysFiltres = $xpath->query($query);

    /*
     * Parcours de la liste des pays récupérés
     */
    foreach ($listePaysFiltres as $elem) {
        /*
         * Création de l'élément pays
         */
        $pays = $resultDom->createElement('pays');

        /*
         * Récupération des différents attributs à ajouter
         */
        $nomPays = $xpath->query('name', $elem)->item(0)->nodeValue;
        $codeCapitalePays = $xpath->query("/mondial/country[./name = '".$nomPays."']/@capital")->item(0)->nodeValue;
        $capitalePays = $xpath->query("/mondial/country[./name = '".$nomPays."']/province/city[@id = '".$codeCapitalePays."']/name")->item(0)->nodeValue;
        $proportionAsie = $xpath->query("/mondial/country[./name = '".$nomPays."']/encompassed[@continent = 'asia']/@percentage")->item(0)->nodeValue;
        $proportionAutre = 100 - $proportionAsie;

        /*
         * Ajout des valeurs des attributs à la balise pays
         */
        $pays->setAttribute('nom',$nomPays);
        $pays->setAttribute('capitale', $capitalePays);
        $pays->setAttribute('proportion-asie', $proportionAsie);
        $pays->setAttribute('proportion-autres', $proportionAutre);

        /*
         * Ajout de l'élément pays à la fin de la liste
         */
        $listePays -> appendChild($pays);
    }

    /*
     * Ajout de la liste au document
     */
    $resultDom->appendChild($listePays);

    /*
     * Affichage du contenu du document
     */
    echo $resultDom->saveXML();
?>
