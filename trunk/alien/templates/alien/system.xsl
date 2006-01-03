<?xml version="1.0" encoding="windows-1251" ?> 
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<xsl:apply-templates />
	</xsl:template>
	<xsl:template match="plugin">
    	<script language="php">$document->renderPlugin("<xsl:value-of select="@name"/>", "<xsl:value-of select="@settings"/>");</script>
	</xsl:template>

	<xsl:template match="phpvar">
		<script language="php">echo $<xsl:apply-templates/></script>
	</xsl:template>
</xsl:stylesheet>