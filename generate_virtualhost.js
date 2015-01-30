function generate_virtualhost(element) {

	var data = "fqdn_vhost="+document.form_vhost.fqdn_vhost.value;
	var data = data+"&description_vhost="+document.form_vhost.description_vhost.value;
	//var data = data+"&email_vhost="+document.form_vhost.email_vhost.value;
	var data = data+"&log_vhost="+document.form_vhost.log_vhost.checked;
	var data = data+"&http_vhost="+document.form_vhost.http_vhost.checked;
	var data = data+"&redir_https_vhost="+document.form_vhost.redir_https_vhost.checked;
	var data = data+"&https_vhost="+document.form_vhost.https_vhost.checked;
	var data = data+"&auth_vhost="+document.form_vhost.auth_vhost.value;

	for (var i=0; i < document.form_vhost.special_vhost.length; i++) {
		if ( document.form_vhost.special_vhost[i].checked) {
			var data = data+"&special_vhost="+document.form_vhost.special_vhost[i].value;
		}
	}

	var url = "generate_virtualhost.php";
	
	AjaxPost(element, url, data, storing, ajaxLoading);
}


function ajaxLoading(element, control) {
        if(control == true) {
                var data = "<img src=\"loading.gif\"/>";
        	storing(data, element);
	}
}
