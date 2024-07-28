<?php
function render($xslPath, $xmlData)
{
    if (!($xmlData instanceof DOMDocument)) {
        $tempXmlData = new DOMDocument();
        $tempXmlData->loadXML($xmlData);
        $xmlData = $tempXmlData;
    }

    $xsl = new DOMDocument();
    $xsl->load($xslPath);

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);

    return $proc->transformToXML($xmlData);
}
?>