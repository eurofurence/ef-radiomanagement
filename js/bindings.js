/* This file contains javascript-functions used on bindings-page */

/* deleteBinding()
 *
 * This function submits the deleteBinding-Form
 *
 * @param bindingid The desired binding to delete
 */
function deleteBinding(bindingid) {
    //Ask user is he is sure
    if(!confirm("Do you really want to delete the binding with the ID "+bindingid+"?")) { return false; }

    //Set deviceid-field in form
    document.getElementById("deleteBindingForm_bindingid").value = bindingid;

    //Append search parameter
    /*var newAction = document.getElementById("deleteBindingForm").action +
                    "&sfield=" + document.getElementById("sfield").options[document.getElementById("sfield").selectedIndex].value +
                    "&svalue=" + document.getElementById("svalue").value;
    document.getElementById("deleteBindingForm").action = newAction;*/

    //Submit Form
    document.getElementById("deleteBindingForm").submit();

    return true;
}