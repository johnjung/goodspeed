<?xml version="1.0"?>
<xsl:stylesheet
 version="2.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output 
 indent="yes"/>

<xsl:template match="text()"/>

<xsl:template match="/dataroot/tblObjectStructuralRev[document='0931']">
<xsl:value-of select="object"/><xsl:text>
</xsl:text>
</xsl:template>

</xsl:stylesheet> 
