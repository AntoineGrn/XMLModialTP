<?php
//Fichier de type XML
header("Content-type: application/xml");
//Création d'un doc DOM qui representera le fichier mondial
$documentXml = new DOMDocument();
//Validation du document DOM
$documentXml->validateOnParse = true;
//Conservation des espace
$documentXml->preserveWhiteSpace=false;
//Mise en forme de la sortie
$documentXml->formatOutput = true;
//chargement du bon fichier xml
$documentXml->load("mondial.xml");

//Création d'un nouveau document pour le fichier de sortie
$newDom = new DOMDocument();
//Element racine
$racine = $newDom->createElement('liste-pays');
//On récupère tous les pays contenu dans le fichier mondial.xml
$listePays = $documentXml->getElementsByTagName("country");

//traitement sur les pays
foreach ($listePays as $pays) {
    //init des variables
    $countryAsia = false;
    $country_name = '';
    $country_city = '';
    $country_percentage = '';
    //traitement sur toutes las balises contenues dans una balise pays
    foreach ($pays->childNodes as $country) {
        //si c'est une balise name, on récupère le nom du pays
        if($country->tagName == 'name') {
            $country_name = $country->nodeValue;
        } elseif ($country->tagName == 'city') { //Si c'est une balise city, et que c'est une capitale on récupère le nom de la ville
            if($country->hasAttribute = 'is_country_cap'){
                foreach ($country->childNodes as $proprietes) {
                    if ($proprietes->tagName == 'name') {
                        $country_city = $proprietes->nodeValue;
                    }
                }
            }
        } elseif ($country->tagName == 'encompassed') {//si c'est une balise encompassed, que le continent est asiatique et que le % est différent de 100
            if ($country->getAttribute('continent') == "asia" && $country->getAttribute('percentage') != "100") {
                $countryAsia = true;
                //récupération du pourcentage
                $country_percentage = $country->getAttribute('percentage');
            }
        }
    }
    //Si on a valider que c'était un pays asiatique, on créer un nouvel élément pays et on set les différents attributs
    if ($countryAsia) {
        $pays = $newDom->createElement('pays');
        $pays->setAttribute('nom', $country_name);
        $pays->setAttribute('capitale', $country_city);
        $pays->setAttribute('proportion-asie', $country_percentage);
        $pays->setAttribute('proportion-autre', 100-$country_percentage);
        //On ajoute à la racine un nouveau fils, l'élément qu'on vient de créer
        $racine->appendChild($pays);
    }
}
//On ajouter au fichier de sortie la racine avec ses enfants
$newDom->appendChild($racine);
//afficher en sortie le résultat XML
echo $newDom->saveXML();
?>
