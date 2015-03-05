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

    //Submit Form
    document.getElementById("deleteBindingForm").submit();

    return true;
}