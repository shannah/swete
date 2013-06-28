<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:import href="/Users/shannah/Documents/Shared/xataface/docbook-xsl/html/chunk.xsl"/>
	<xsl:param name="html.stylesheet" select="'style.css'"/>
	
	
	<!-- ==================================================================== -->
	<xsl:template name="chunk-element-content">
	  <xsl:param name="prev"/>
	  <xsl:param name="next"/>
	  <xsl:param name="nav.context"/>
	  <xsl:param name="content">
		<xsl:apply-imports/>
	  </xsl:param>
	
	  <xsl:call-template name="user.preroot"/>
	
	  <html>
		<xsl:call-template name="root.attributes"/>
		<xsl:call-template name="html.head">
		  <xsl:with-param name="prev" select="$prev"/>
		  <xsl:with-param name="next" select="$next"/>
		</xsl:call-template>
	
		<body>
		  <xsl:call-template name="body.attributes"/>
		  <div id="wrapper">
			<div id="top-bar">
				<span id="top-left"></span>
				<span id="top-right"></span>
			</div>
			<div id="inner-wrapper">
	
				<div id="heading">
	
						<div id="languages">
	
							<h2>Simple Website Translation Engine</h2>
						</div>
						<div id="site-network">
	
						<div id="addthis">
						<!-- AddThis Button BEGIN -->
							<script type="text/javascript">addthis_pub  = 'shannah';</script>
							<a href="http://www.addthis.com/bookmark.php" onmouseover="return addthis_open(this, '', '[URL]', '[TITLE]')" onmouseout="addthis_close()" onclick="return addthis_sendto()"><img src="http://s9.addthis.com/button1-addthis.gif" alt="" border="0" height="16" width="125" /></a><script type="text/javascript" src="http://s7.addthis.com/js/152/addthis_widget.js"></script>
						<!-- AddThis Button END -->
						</div>
	
						<h4></h4>
						<ul>
							<li><!--<a href="signup.html" title="Sign up Now for Free">Sign Up </a>--></li>
							<li><!--<a href="http://translation.weblite.ca" title="Web Lite SWeTE Administration Console">My Account </a>--></li>
						</ul>
	
						<div style="clear: both;"></div>
	
					</div>
	
					<h1><span>Web Lite Solutions Corp.</span></h1>
	
					<div id="fb-links">
						<a href="https://www.facebook.com/pages/Web-Lite-SWeTE/195772450420"><img src="images/Facebook_icon-1.png" alt="SWeTE Facebook Page" /></a>
						<a href="https://twitter.com/WebLiteSWeTE"><img src="images/TwitterIcon-1.png" alt="Follow SWeTE on Twitter" /></a>
					</div>
	
					<ul class="nav-menu">
						<li><a href="http://swete.weblite.ca/home">Home</a></li>
						<li><a href="http://swete.weblite.ca/about">About</a></li>
						<li><a href="http://swete.weblite.ca/documentation">Documentation</a></li>
						<li><a href="http://swete.weblite.ca/download">Download</a></li>
						<li><a href="http://swete.weblite.ca/contact">Help</a></li>
					</ul>
	
				</div>
	
				<div id="main">
				  <xsl:call-template name="user.header.navigation">
					<xsl:with-param name="prev" select="$prev"/>
					<xsl:with-param name="next" select="$next"/>
					<xsl:with-param name="nav.context" select="$nav.context"/>
				  </xsl:call-template>
			
				  <xsl:call-template name="header.navigation">
					<xsl:with-param name="prev" select="$prev"/>
					<xsl:with-param name="next" select="$next"/>
					<xsl:with-param name="nav.context" select="$nav.context"/>
				  </xsl:call-template>
			
				  <xsl:call-template name="user.header.content"/>
			
				  <xsl:copy-of select="$content"/>
			
				  <xsl:call-template name="user.footer.content"/>
			
				  <xsl:call-template name="footer.navigation">
					<xsl:with-param name="prev" select="$prev"/>
					<xsl:with-param name="next" select="$next"/>
					<xsl:with-param name="nav.context" select="$nav.context"/>
				  </xsl:call-template>
			
				  <xsl:call-template name="user.footer.navigation">
					<xsl:with-param name="prev" select="$prev"/>
					<xsl:with-param name="next" select="$next"/>
					<xsl:with-param name="nav.context" select="$nav.context"/>
				  </xsl:call-template>
		  
				</div>
	
				<div id="bottom-bar">
					<span id="bottom-left"></span> 
					<span id="bottom-right"></span>
				</div>
			</div>

			<script type="text/javascript"><![CDATA[
				var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
				document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
			]]></script>
			<script type="text/javascript"><![CDATA[
				try {
					var pageTracker = _gat._getTracker("UA-513831-7");
					pageTracker._trackPageview();
				} catch(err) {}
			]]></script>
			<script src="js/jquery.js"></script>
			<script src="js/jquery.lightbox-0.5.pack.js"></script>
			<script><![CDATA[
				$('.mediaobject > img').each(function(){
					$(this).wrap('<a href="'+$(this).attr('src')+'"></a>').parent().lightBox();
				});
			]]></script>
		</div>
		</body>
	  </html>
	  <xsl:value-of select="$chunk.append"/>
	</xsl:template>
	
</xsl:stylesheet>