/***********************************************************
 *
 * Lib Ajax - Gamb@burgbrau.com
 *
 ***********************************************************/


/**
 * Creation d'un objet ajax     
 */
function AjaxCreate()
{
    var request = false;
        try {
            request = new ActiveXObject('Msxml2.XMLHTTP');
        }
        catch (err2) {
            try {
                request = new ActiveXObject('Microsoft.XMLHTTP');
            }
            catch (err3) {
                try {
                        request = new XMLHttpRequest();
                }
                catch (err1)
                {
                        request = false;
                }
            }
        }
    return request;
}

/**
 *  * Requete POST avec action de retour
 *   */
function AjaxPost(element, url, data, funcPrinting, funcLoading)
{
        var xhr = AjaxCreate();

        xhr.onreadystatechange=function()
        {
                /* Si reponse recu */
                if(xhr.readyState == 4)
                {
                        /* On stop le decompte du timeout */
                        clearTimeout(timeout);

                        /* On enleve la notification de chargement */
                        if(funcLoading != false)
                        {
                                funcLoading(element, false);
                        }

                        if(xhr.status == 200)
                        {
                                if(funcLoading != false)
                                {
                                        funcLoading(element, "0");
                                }
                                var content = xhr.responseText;
                                /* Affichage de la reponse */
                                funcPrinting(content, element);
                        }
                }
                else
                {
                        /* On affiche la notification de chargement */
                        if(funcLoading != false)
                        {
                                funcLoading(element, true);
                        }
                }
        };
        xhr.open("POST", url , true);
        xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded charset=utf-8");
        xhr.send(data);

        /* Gestion timeout */
        var timeout = setTimeout(function() {funcPrinting("<div id=\"error\">Ajax : Le serveur ne r&eacute;pond pas.</div>", element);xhr.abort();}, 20000);
}

/**
 *  * Fonctions d'affichage
 *   */
        /* Affichage d'un contenu HTML */
        function storing(data, element)
        {
                element.innerHTML = data;
        }

        /* Affichage d'une chaine de caracteres */
        function writing(data, element)
        {
                element.value = data;
        }

