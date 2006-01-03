<?xml version="1.0" encoding="windows-1251"?>
<xsl:stylesheet version="1.0"
   xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="system.xsl"/>  
   <xsl:output method="html"/>
   <xsl:template match="page">
      <html>
       	<xsl:apply-templates />
      </html>
   </xsl:template>
   
   <xsl:template match="head">
   	<head>
            	<title><xsl:value-of select="title"/></title>
	</head>
	<link rel="stylesheet" href="/templates/alien/style.css" type="text/css" />
	<script type="text/javascript" src="js/functionAddEvent.js"></script>
	<script type="text/javascript" src="js/toolTipLib.js"></script>
   </xsl:template>
   
   <xsl:template match="body">
         <body>
	 <table width="95%" align="center" cellpadding="4" cellspacing="0">
	 <tr>
	 	<td class="header" align="left">Alien News</td>
	 </tr>
	 <tr>
	 	<td align='left'><xsl:apply-templates/>
</td>
	 </tr>
	 </table>
         </body>
   </xsl:template>

    <xsl:template match="center">
	<center><xsl:apply-templates/></center>
    </xsl:template>
    
    <xsl:template match="title">
	<div><b><xsl:apply-templates/></b></div>
    </xsl:template>
    
    <xsl:template match="text">
    <span style="float:left"><img src="/templates/alien/images/edit_32.png" style="margin-right:10px;" /></span><xsl:apply-templates/><BR />
    </xsl:template>
    
    <xsl:template match="infobar">
	<i><xsl:apply-templates/></i><BR /><BR />
    </xsl:template>
    
    <xsl:template match="link">
	<a href="{@href}"><xsl:apply-templates/></a>
    </xsl:template>
    
    <xsl:template match="img">
	<img src="{@src}" border="{@border}" /><xsl:apply-templates/>
    </xsl:template>
    
    <xsl:template match="b">
	<b><xsl:apply-templates/></b>
    </xsl:template>
    
    <xsl:template match="quote">
	<blockquote><xsl:apply-templates/></blockquote>
    </xsl:template>

</xsl:stylesheet>