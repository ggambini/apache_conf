/*
 *  * Affiche ou masque un element
 *   */
function setVisible(elementId)
{
        var targetElement;

        targetElement = document.getElementById(elementId) ;

        if (targetElement.style.display == "none")
        {
                targetElement.style.display = "" ;
        } else {
                targetElement.style.display = "none" ;
        }
}

/*
 *  * Surbrillance
 *   */
function highlight(target, theStyle) {

	// Support des styles
	if(typeof(target.className) == 'undefined') {
                return false;
        }

	// On applique le style
	target.className = theStyle;
	return true;
}

/*
 * Confirmation
 */
function confirmAction(message,action){
        if(confirm(message)){
                document.location.href=action;
        }
}
