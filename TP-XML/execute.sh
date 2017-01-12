#!/bin/bash
# Programme qui lance à la suite les différents programmes

echo "Question 1 : Exécution du programme xslt dans un environnement php (avec temps d'éxécution) V2.0: "
time php question1.php
echo "Question 1 : Exécution du programme xslt dans un environnement php (avec temps d'éxécution) V1.0: "
time java -jar SaxonHE9-7-0-14J/saxon9he.jar -xsl:question1.xsl -s:mondial/mondial.xml -TP:profile.html
echo "Question 2 : Programme DOM sans utiliser XPath : "
time php question2DOM.php
echo "Question 2 : Programme DOM en utilisant XPath : "
time php question2DOMXpath.php
echo "Question 2 : Programme SAX : "
time php question2SAX.php
echo "Question 4 : Programme utilisant les objects XMLReader et XMLWriter : "
time php question4.php

exit 0
