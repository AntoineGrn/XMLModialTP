<?php
echo 'coucou';
   $xslDoc = new DOMDocument();
   $xslDoc->load("question1.xsl");

   $xmlDoc = new DOMDocument();
   $xmlDoc->load("mondial/mondial.xml");

   $proc = new XSLTProcessor();
   $proc->importStylesheet($xslDoc);
   echo($proc->transformToXML($xmlDoc));
?>
