<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <!-- works
  <xsl:template match="@*|node()">
    <xsl:copy>
      <xsl:apply-templates select="@*|node()"/>
    </xsl:copy>
  </xsl:template>
-->

  <xsl:template match="/">
    <text>
      <xsl:apply-templates/>
    </text>
  </xsl:template>

  <xsl:template match="node()">
    <xsl:apply-templates select="node()|text()"/>
  </xsl:template>

  <xsl:template match="text()">
    <xsl:copy/>
  </xsl:template>
</xsl:stylesheet>
