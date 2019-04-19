<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output omit-xml-declaration="yes"/>

<!-- ACQUISITION -->
<xsl:template match="acquisition">
<h3>Acquisition</h3><xsl:apply-templates/>
</xsl:template>

<!-- ADDITIONS -->
<xsl:template match="additions">
<br/>Additions: <xsl:value-of select="."/>
</xsl:template>

<!-- BIBL -->
<xsl:template match="bibl">
<li><xsl:apply-templates/></li>
</xsl:template>

<!-- BINDINGDESC -->
<xsl:template match="bindingDesc">
<h3>Binding Description</h3><xsl:apply-templates/>
</xsl:template>

<!-- CANON TABLES -->
<xsl:template match="canonTables">
<h3>Canon Tables</h3><xsl:apply-templates/>
</xsl:template>

<!-- CATCHWORDS -->
<xsl:template match="catchwords">
<h3>Catchwords</h3><p><xsl:apply-templates/></p>
</xsl:template>

<!-- COLLATION -->
<xsl:template match="collation">
<h3>Collation</h3><xsl:apply-templates/>
</xsl:template>

<!-- COLOPHON -->
<xsl:template match="colophon">
<br/>Colophon: <xsl:apply-templates/>
</xsl:template>

<!-- COMMENTS -->
<xsl:template match="comments">
<h3>Comments</h3><xsl:apply-templates/>
</xsl:template>

<!-- CONDITION -->
<xsl:template match="condition">
<h3>Condition</h3><xsl:apply-templates/>
</xsl:template>

<!-- CONTENTS -->
<xsl:template match="msContents">
<h2>Contents</h2><xsl:apply-templates/>
</xsl:template>

<!-- DECORATION -->
<xsl:template match="decoration">
<h2>Decoration</h2><xsl:apply-templates/>
</xsl:template>

<!-- DIMENSIONS -->
<xsl:template match="dimensions[@type='written']">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="dimensions[@type='written']">
<h3>Dimensions</h3><p><xsl:apply-templates/></p>
</xsl:template>

<!-- EXTENT -->
<xsl:template match="extent">
<h3>Number of Leaves</h3><p><xsl:apply-templates/></p>
</xsl:template>

<!-- FOLIATION -->
<xsl:template match="foliation">
<h3>Foliation</h3><xsl:apply-templates/>
</xsl:template>

<!-- HEADPIECES -->
<xsl:template match="headpieces">
<h3>Headpieces</h3><xsl:apply-templates/>
</xsl:template>

<!-- HI@REND='ITALICS' -->
<xsl:template match="hi[@rend='italics']">
<em><xsl:apply-templates/></em>
</xsl:template>

<!-- HISTORY -->
<xsl:template match="history">
<h2>History</h2><xsl:apply-templates/>
</xsl:template>

<!-- INITIALS -->
<xsl:template match="initials">
<h3>Initials</h3><xsl:apply-templates/>
</xsl:template>

<!-- LAYOUT -->
<xsl:template match="layout">
<h3>Layout</h3><xsl:apply-templates/>
</xsl:template>

<!-- LB -->
<xsl:template match="lb">
<br/>
</xsl:template>

<!-- LISTBIBL -->
<xsl:template match="listBibl">
<h2>Bibliography</h2><ol><xsl:apply-templates/></ol>
</xsl:template>

<!-- LOCUS -->
<xsl:template match="locus">
<span class="foliation">
<a href="../view/index.php?doc={substring(/TEI.2/text/body/msDescription/@id,5)}&amp;obj={@from}"><xsl:value-of select="."/></a>
</span>
</xsl:template>

<!-- MINDESC -->
<xsl:template match="minDesc">
<xsl:apply-templates/>
<xsl:text>
</xsl:text>
</xsl:template>

<!-- MINIATURES -->
<xsl:template match="miniatures">
<h3>Miniatures</h3><xsl:apply-templates/>
</xsl:template>

<!-- MINITEM -->
<xsl:template match="minItem[position()=1]">
<ol>
<xsl:for-each select=". | following-sibling::minItem">
<li>
<xsl:apply-templates select="locus"/>
<xsl:apply-templates select="minDesc|note"/>
</li>
</xsl:for-each>
</ol>
</xsl:template>
<xsl:template match="minItem"/>

<!-- MSIDENTIFIER -->
<xsl:template match="msDescription/msIdentifier">
</xsl:template>

<!-- MSITEM -->
<xsl:template match="msItem[position()=1]">
<ol>
<xsl:for-each select=". | following-sibling::msItem">
<li><xsl:apply-templates/></li>
</xsl:for-each>
</ol>
</xsl:template>
<xsl:template match="msItem"/>

<!-- MSHEADING -->
<xsl:template match="msHeading"/>

<!-- MSWRITING -->
<xsl:template match="msWriting">
<h3>Writing</h3><xsl:apply-templates/>
</xsl:template>

<!-- NAME -->
<xsl:template match="name">
<xsl:value-of select="."/>
</xsl:template>

<!-- NOTE -->
<xsl:template match="note">
<br/>Note: <xsl:apply-templates/>
</xsl:template>

<xsl:template match="listBibl//note">
<xsl:apply-templates/>
</xsl:template>

<!-- ORIGIN -->
<xsl:template match="origin">
<h3>Origin</h3><xsl:apply-templates/>
</xsl:template>

<!-- P -->
<xsl:template match="p">
<xsl:copy><xsl:apply-templates/></xsl:copy>
</xsl:template>

<!-- PHYSDESC -->
<xsl:template match="physDesc">
<h2>Physical Description</h2>
<xsl:apply-templates/>  
</xsl:template>

<!-- PROVENANCE -->
<xsl:template match="provenance">
<h3>Provenance</h3><xsl:apply-templates/>
</xsl:template>

<!-- RUBRIC -->
<xsl:template match="rubric">
<br/>Rubric: <xsl:apply-templates/>
</xsl:template>

<!-- SECFOL -->
<xsl:template match="secFol">
<h3>Secundo Folio</h3><p><xsl:apply-templates/></p>
</xsl:template>

<!-- SUPPORT -->
<xsl:template match="support">
<h3>Support</h3><xsl:apply-templates/>
</xsl:template>

<!-- TEXTDIVISIONS -->
<xsl:template match="textDivisions">
<h3>Text Divisions</h3><xsl:apply-templates/>
</xsl:template>

<!-- TEIHEADER -->
<xsl:template match="teiHeader"/>

<!-- TITLE -->
<xsl:template match="title">
<em><xsl:value-of select="."/></em>
</xsl:template>

<xsl:template match="title[@level='a']">
<xsl:value-of select="."/>
</xsl:template>

<xsl:template match="title[@level='s']">
<xsl:value-of select="."/>
</xsl:template>

<xsl:template match="title[@level='u']">
<xsl:value-of select="."/>
</xsl:template>

<!-- WATERMARKS -->
<xsl:template match="watermarks">
<h3>Watermarks</h3><xsl:apply-templates/>
</xsl:template>
</xsl:stylesheet>
