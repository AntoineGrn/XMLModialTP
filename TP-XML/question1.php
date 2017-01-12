<?php
   $xslDoc = new DOMDocument();
   $xslDoc->load("question1v1.xsl");

   $xmlDoc = new DOMDocument();
   $xmlDoc->load("mondial/mondial.xml");

   $proc = new XSLTProcessor();
   $proc->importStylesheet($xslDoc);
   echo($proc->transformToXML($xmlDoc));
?>
