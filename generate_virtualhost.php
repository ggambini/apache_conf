<?php

// Init
        header('Content-type: text/html; charset=utf-8');

// Parametres
	$fqdn_vhost = $_POST['fqdn_vhost'];
        $description_vhost = $_POST['description_vhost'];
        //$email_vhost = $_POST['email_vhost'];
        $log_vhost = $_POST['log_vhost'];
	$http_vhost = $_POST['http_vhost'];
	$redir_https_vhost = $_POST['redir_https_vhost'];
	$https_vhost = $_POST['https_vhost'];
	$special_vhost = $_POST['special_vhost'];
	$auth_vhost = $_POST['auth_vhost'];


	// On calcul le nom court du vhost
	$name_vhost = explode(".", $fqdn_vhost, 2);
	$domain_vhost = $name_vhost[1];
	$name_vhost = $name_vhost[0];

	// Une tabulation :D
	$t = "	";

// Traitement

// Entete
	$out_entete = "#\n# VirtualHost $fqdn_vhost\n# $description_vhost\n#\n";

// Fichiers log
	$out_logfiles = $t.$t."CustomLog /var/log/httpd/$name_vhost-access.log combined\n";
	$out_logfiles .= $t.$t."ErrorLog /var/log/httpd/$name_vhost-error.log\n";
	$out_logfiles .= $t.$t."LogLevel warn\n";

// Authentifications
	// CAS
	if($auth_vhost == "cas") {
		$out_auth = $t.$t.$t."AuthType CAS\n";
                $out_auth .= $t.$t.$t."AuthName \"$description_vhost\"\n";
                $out_auth .= $t.$t.$t."Require valid-user\n";

	}
	// SHibb
	if($auth_vhost == "shibb") {
		$out_auth = $t.$t.$t."AuthType shibboleth\n";
		$out_auth .= $t.$t.$t."ShibRequestSetting requireSession 1\n";
		$out_auth .= $t.$t.$t."ShibRequestSetting applicationId default\n";
		$out_auth .= $t.$t.$t."Require valid-user\n";
        }


// Directory /
	$out_dir = $t.$t."DocumentRoot /var/www/$name_vhost\n";
        $out_dir .= $t.$t."<Directory />\n";
	if($special_vhost == "cgi") {
		$out_dir .= $t.$t.$t."AddHandler perl-script .cgi\n";
		$out_dir .= $t.$t.$t."Options FollowSymLinks ExecCGI\n";
	}else{
	        $out_dir .= $t.$t.$t."Options FollowSymLinks\n";
	}
	$out_dir .= $t.$t.$t."AllowOverride none\n";
        $out_dir .= $t.$t.$t."Order allow,deny\n";
        $out_dir .= $t.$t.$t."Allow from all\n";
	// Auth
	if($auth_vhost != "") {
		$out_dir .= $t.$t.$t."\n";
		$out_dir .= $out_auth;
	}
	// Secu PHP
	if($special_vhost == "php") {
		$out_dir .= $t.$t.$t."\n";
		$out_dir .= $t.$t.$t."php_admin_flag safemode 1\n";
		$out_dir .= $t.$t.$t."php_admin_value open_basedir \"/var/www/$name_vhost:/tmp/\"\n";
		$out_dir .= $t.$t.$t."php_admin_value include_path \".:/usr/share/pear\"\n";
	}
        $out_dir .= $t.$t."</Directory>\n";

// Location / for Reverse Proxy
	$out_loc = $t.$t."<Location />\n";
        $out_loc .= $t.$t.$t."Order allow,deny\n";
        $out_loc .= $t.$t.$t."Allow from all\n";
	// Auth
        if($auth_vhost != "") {
                $out_loc .= $t.$t.$t."\n";
                $out_loc .= $out_auth;
        }

	$out_loc .= $t.$t.$t."\n";
	$out_loc .= $t.$t.$t."ProxyPass http://localhost:8080/\n";
	$out_loc .= $t.$t.$t."ProxyPassReverse http://localhost:8080/\n";
	$out_loc .= $t.$t."</Location>\n";

// Redir HTTP -> HTTPS
	$out_redir = $t.$t."RedirectMatch permanent ^/(.*)$ https://$fqdn_vhost/$1\n";

// Partie HTTP
	if($http_vhost == "true") {
		$out_http = $t."<VirtualHost *:80>\n"; 
		$out_http .= $t.$t."ServerName $fqdn_vhost\n";
		$out_http .= $t.$t."ServerAlias $name_vhost\n";
		$out_http .= $t.$t."ServerAdmin admin@$domain_vhost\n";
		$out_http .= $t.$t."\n";

		if($redir_https_vhost == "true" AND $https_vhost == "true") {
			$out_http .= $out_redir;
		}elseif($special_vhost == "rproxy") {
			$out_http .= $out_loc;
		}else{
			$out_http .= $out_dir;
		}

		// Ajout log si activé
		if($log_vhost == "true") {
			$out_http .= "\n".$out_logfiles;
		}

		$out_http .= $t."</VirtualHost>\n";

		if($https_vhost == "true") {
			// Un saut de ligne si il y a un vhost 443 en plus du 80
			$out_http .= "\n";
		}
	}

// Partie HTTPS
        if($https_vhost == "true") {
                $out_https = $t."<VirtualHost *:443>\n"; 
                $out_https .= $t.$t."ServerName $fqdn_vhost\n";
                $out_https .= $t.$t."ServerAlias $name_vhost\n";
                $out_https .= $t.$t."ServerAdmin admin@$domain_vhost\n";
                $out_https .= $t.$t."\n";
		if($special_vhost == "rproxy") {
                        $out_https .= $out_loc."\n";
                }else{
	                $out_https .= $out_dir."\n";
		}

		// Partie mod_ssl
		$out_https .= $t.$t."SSLEngine on\n";
		$out_https .= $t.$t."SSLCertificateFile /etc/httpd/conf/ssl/$fqdn_vhost.crt\n";
		$out_https .= $t.$t."SSLCertificateChainFile /etc/httpd/conf/ssl/cachain.crt\n";
		$out_https .= $t.$t."SSLCertificateKeyFile /etc/httpd/conf/ssl/key/$fqdn_vhost.key\n";

		// Ajout log si activé
                if($log_vhost == "true") {
                        $out_https .= "\n".$out_logfiles;
                }

                $out_https .= $t."</VirtualHost>\n";

        }








// Affichage
	$output = "<pre>".htmlentities($out_entete.$out_http.$out_https)."</pre>";
	echo utf8_encode($output);
?>
