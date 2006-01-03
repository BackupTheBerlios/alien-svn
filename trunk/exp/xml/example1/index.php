<?php

$xsl = new DomDocument();
$xsl->load("style.xslt");
$inputdom = new DomDocument();
$inputdom->load("doc.xml");
$proc = new XsltProcessor();
$xsl = $proc->importStylesheet($xsl);
/* transform and output the xml document */
$newdom = $proc->transformToDoc($inputdom);
echo $newdom->saveXML();

?>