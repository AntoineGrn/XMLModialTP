<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" indent="yes"/>
    <xsl:template match="/">
        <liste-pays>
            <xsl:apply-templates select="./mondial/country[encompassed[@continent = 'asia' and number(@percentage) < 100]]"/>
        </liste-pays>
    </xsl:template>

    <xsl:template match="country">
        <xsl:element name="pays">
            <xsl:attribute name="nom" select="name"></xsl:attribute>
            <xsl:attribute name="capitale">
                <xsl:value-of select="descendant::city[@is_country_cap='yes']/name"/>
            </xsl:attribute>
            <xsl:attribute name="proportion-asie" select="encompassed[@continent = 'asia']/@percentage"></xsl:attribute>
            <xsl:attribute name="proportion-autres" select="number(100 - encompassed[@continent = 'asia']/@percentage)"></xsl:attribute>
        </xsl:element>
    </xsl:template>
</xsl:stylesheet>
