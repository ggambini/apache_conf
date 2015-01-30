<?php
	// INIT
        header('Content-type: text/html; charset=utf-8');

	// Ajout le javascript qui affiche ou masque l'info bulle
	function js_infoParam($div_id) {
		echo "onclick= \"setVisible('$div_id');\" style=\"cursor: help\" title=\"Cliquer pour plus d'info sur ce paramètre\"";
	}

	function js_genVHost() {
		echo "onChange=\"generate_virtualhost(document.getElementById('result'))\"";
	}

	function infoParam($div_id, $text) {
		echo "<div class=\"info\" id=\"$div_id\" style=\"display:none;\">\n$text\n</div>";
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Générateur de VirtualHosts pour Apache</title>
	<link href="style.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="ajax.js"></script>
	<script type="text/javascript" src="common.js"></script>
	<script type="text/javascript" src="generate_virtualhost.js"></script>
</head>

<body>
	<div id="form">
	<fieldset><legend>Générateur de VirtualHosts pour Apache</legend>
		Ce petit outil permet de générer rapidement une configuration. Il peut aussi permettre de comprendre les différentes options utilisables. Cliquer sur le nom d'un paramètre pour avoir des détails.<br/>
		La convention de nommage et le paramètrage général du serveur sont décris dans <a href="http://blog.gamb.fr/index.php?post/2011/10/21/Tout-sur-le-name-based-virtual-host-!">cet article</a>.
	</fieldset>
	<form name="form_vhost" action="" method="post">
		<fieldset>
			<legend>Paramètres globaux</legend>
			<table>
				<tr>
					<td><a <?php js_infoParam('info_fqdn_vhost'); ?>>Nom complet du serveur</a>
					<?php infoParam('info_fqdn_vhost', "Nom complet sur lequel devra répondre Apache, c'est à dire avec le nom de domaine. Apache utilise le nom pour différencier les virtualhosts.<br/>Exemple : monsite.projet-plume.org"); ?>
					</td>
					<td>
						<input type="text" name="fqdn_vhost" value="" autofocus="autofocus" <?php js_genVHost(); ?>/>
					</td>
				</tr>
				<tr>
					<td><a <?php js_infoParam('info_description_vhost'); ?>>Description courte</a>
					<?php infoParam('info_description_vhost', 'Une courte description du service rendu par ce virtual host'); ?>
					</td>
					<td><input type="text" name="description_vhost" value="" <?php js_genVHost(); ?>/></td>
				</tr>
<!--				<tr>
					<td><a <?php js_infoParam("info_email_vhost"); ?>>Administrateur</a>
					<?php infoParam("info_email_vhost", "Simplement une adresse mail pour contacter l'administrateur"); ?>
					</td>
					<td><input type="text" name="email_vhost" value="" <?php js_genVHost(); ?>/></td>
				</tr>-->
				<tr>
					<td><a <?php js_infoParam("info_log_vhost"); ?>>Fichiers de log dédiés</a>
					<?php infoParam("info_log_vhost", "Journalise les accès et les erreurs dans des fichiers spécifiques pour ce virtual host"); ?>
					</td>
					<td><input type="checkbox" name="log_vhost" <?php js_genVHost(); ?>/></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
                        <legend>Protocole HTTP</legend>
			<table>
				<tr>
					<td><a <?php js_infoParam("info_http_vhost"); ?>>Ecouter sur le port 80</a>
					<?php infoParam("info_http_vhost", "Ecouter sur le port 80 signifie qu'Apache répondra aux requêtes HTTP."); ?>
					</td>
					<td><input type="checkbox" name="http_vhost" checked <?php js_genVHost(); ?>/></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
                        <legend>Protocole HTTPS</legend>
			<table>
				<tr>
					<td><a <?php js_infoParam("info_https_vhost"); ?>>Ecouter sur le port 443</a>
					<?php infoParam("info_https_vhost", "Ecouter sur le port 443 signifie qu'Apache répondra aux requêtes HTTPS."); ?>
					</td>
					<td><input type="checkbox" name="https_vhost" <?php js_genVHost(); ?>/></td>
				</tr>
				<tr>
                                        <td><a <?php js_infoParam("info_redir_https_vhost"); ?>>Rediriger tout vers le port 443 (HTTPS)</a>
                                        <?php infoParam("info_redir_https_vhost", "Renvoi les requêtes HTTP vers le port 443. On force l'utilisateur à utiliser HTTPS."); ?>
                                        </td>
                                        <td><input type="checkbox" name="redir_https_vhost" <?php js_genVHost(); ?>/></td>
                                </tr>
			</table>
                </fieldset>
		<fieldset>
                        <legend>Options spéciales</legend>
			<table>
				<tr>
					<td><a <?php js_infoParam("info_generic_vhost"); ?>>Générique</a>
                                        <?php infoParam("info_generic_vhost", "Configuration basique convenant pour des documents HTML statiques ou des scripts PHP."); ?>
                                        <td><input type="radio" name="special_vhost" value="" checked <?php js_genVHost(); ?>/></td>
				</tr>
				<tr>
					<td><a <?php js_infoParam("info_php_vhost"); ?>>Sécurité PHP</a>
					<?php infoParam("info_php_vhost", "Configuration stricte de PHP. De plus, on chroot l'application dans son DocumentRoot."); ?>
					<td><input type="radio" name="special_vhost" value="php" <?php js_genVHost(); ?>/></td>
				</tr>
				<tr>
                                        <td><a <?php js_infoParam("info_reverse_vhost"); ?>>Reverse proxy</a>
                                        <?php infoParam("info_reverse_vhost", "Apache peut servir de frontal pour d'autres services, par exemple un Tomcat. Le mod_proxy doit être installé et activé."); ?>
                                        <td><input type="radio" name="special_vhost" value="rproxy" <?php js_genVHost(); ?>/></td>
                                </tr>
				<tr>
                                        <td><a <?php js_infoParam("info_cgi_vhost"); ?>>Scripts CGI</a>
                                        <?php infoParam("info_reverse_vhost", ""); ?>
                                        <td><input type="radio" name="special_vhost" value="cgi" <?php js_genVHost(); ?>/></td>
                                </tr>
				<tr>
					<td><a <?php js_infoParam("info_auth_vhost"); ?>>Authentification</a>
					<?php infoParam("info_auth_vhost", "Les utilisateurs devront s'authentifier avant d'accéder aux documents. Apache peut servir par exemple utiliser le SSO de l'établissement"); ?>
					<td><select name="auth_vhost"  <?php js_genVHost(); ?>>
						<option value="">Auncune</option>
						<option value="cas">CAS</option>
						<option value="shibb">Shibboleth</option>
					</select></td>
				</tr>
			</table>
                </fieldset>

	</form>
	</div>

	<div id="result">
	<i>Veuillez saisir au moins un param&egrave;tre ci contre ...</i>
	</div>
</body>
</html>
