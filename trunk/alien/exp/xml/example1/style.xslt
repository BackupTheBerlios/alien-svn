<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0"
   xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

   <xsl:output method="html"/>

   <xsl:template match="chapter">
      <html>
         <head>
            <title><xsl:value-of select="title"/></title>
         </head>
         <body>
            <xsl:apply-templates/>
         </body>
      </html>
   </xsl:template>


   <xsl:template match="title">
      <center>
         <h1><xsl:apply-templates/></h1>
      </center>
   </xsl:template>

   <xsl:template match="section">
      <h3><xsl:value-of select="@title"/></h3>
      <xsl:apply-templates/>
   </xsl:template>

   <xsl:template match="para">
      <p><xsl:apply-templates/></p>
   </xsl:template>

</xsl:stylesheet>