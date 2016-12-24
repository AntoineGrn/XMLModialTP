<?php
header("Content-type: application/xml");
$document_xml = new DOMDocument(); // Instanciation de la classe DOMDocument : création d'un nouvel objet
$document_xml->validateOnParse = true; //on valide le doc XML par rapport à la DTD lors du chargement
$document_xml->preserveWhiteSpace=false; //on ne prend pas en compte les espaces et l'indentation
$document_xml->load("mondial/mondial.xml"); // Chargement de mondial.xml

?>
