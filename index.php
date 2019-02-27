<?php
$fromhome = "1";
include 'functions.php';
?>
<html>
	<head>
		<title>Rig Manager</title>
		<link rel="stylesheet" type="text/css" href="default.css" media="screen" />
		<link rel="shortcut icon" href="./rig-mananger-logo-50.png">
		<meta http-equiv="refresh" content="300" />

		<?php
		//If they are viewing the chart page then include chart.js
		if (isset($_GET['page']) AND $_GET['page'] == 'minerstats') {
			echo "<script src='./Chart.js/Chart.bundle.js'></script>";
			echo "<script src='./Chart.js/samples/utils.js'></script>";
		}
		?>

	</head>
	<!-- Matomo -->
	<script type="text/javascript">
	  var _paq = _paq || [];
	  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
	  _paq.push(['trackPageView']);
	  _paq.push(['enableLinkTracking']);
	  (function() {
	    var u="//szmatomo.azurewebsites.net/";
	    _paq.push(['setTrackerUrl', u+'piwik.php']);
	    _paq.push(['setSiteId', '4']);
	    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
	    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
	  })();
	</script>
	<!-- End Matomo Code -->
<body style="background-color: #000000; margin: auto; text-align: center">
<div style="margin: auto; display: flex;">
<table style="width:auto" id="body">
	<tr>
	  <th>

<?php
if (empty($_SERVER['REMOTE_USER'])) {
    echo "<a href='./'><img style='float:left' src ='./rig-mananger-75h.png'></a><div class='menu'><div>Log in (<a href='/.auth/login/google?post_login_redirect_url=/'>Google</a>)</div><div><a href='./'>Home</a></div>";
} else {
    echo "<a href='./'><img style='float:left' src ='./rig-mananger-75h.png'></a><div class='menu'><div>" . $_SERVER['REMOTE_USER'] . " (<a href='/.auth/logout?post_logout_redirect_uri=/'>Log Out</a>)</div><div><a href='./'>Home</a></div><div><a href='?page=settings'>Settings</a></div>";
}
?>

	  <div><a href="http://docs.rigmanager.xyz/">About</a></div><div><a href="http://docs.rigmanager.xyz/configuring-your-miner-to-work-with-rig-manager/">Setup</a></div><div><a href="http://docs.rigmanager.xyz/frequently-asked-questions/">FAQ</a></div></div>
		</th>
	</tr>
	<tr>
	  <td style="verticle-align:top; width:auto">


<?php
if (isset($_GET['page']) !== true) {
	include 'parts/home.php';
} elseif (isset($_GET['page']) AND $_GET['page'] == 'settings') {
	include 'parts/settings.php';
} elseif (isset($_GET['page']) AND $_GET['page'] == 'signup') {
	include 'parts/signup.php';
} elseif (isset($_GET['page']) AND $_GET['page'] == 'minerstats') {
	include 'parts/minerstats.php';
} else {
	include 'parts/home.php';
}
?>

	  </td>
	</tr>
	<tr>
		<td colspan="1">
			<p>Rig Manager, a hackathon project from your friends at <a href="https://www.freelearner.how" target="_blank">FreeLearner.how</a></p>
			<p>Join us and share your feedback on <a href="https://www.reddit.com/r/RigManager/" target="_blank"><img src="data:image/jpeg;base64,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" width="28"> Reddit</a></p>
			<p>View the source code on <a href="https://github.com/erikszewczyk/RigManager" target="_blank">Github</a></p>
			<p>Any bitcoin donations are greatly appreciated: 3DHjBqPWFZzg6jo6f2DVcZc4t5bZJy38ZH</p>
		</td>
	</tr>
</table>
</div>
	</body>
</html>