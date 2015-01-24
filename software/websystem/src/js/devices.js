/* This file contains javascript-functions used on devices-page */

var lastDeviceTemplateId;
var lastDeviceTemplateName;
var lastDeviceTemplateDescription;
var lastDeviceTemplateNavi;

function resetLastDeviceTemplateEdit() {
    //Check if lastId is present
    if(!lastDeviceTemplateId) { return false; }

    //Revert Changes
    document.getElementById("devicetpl_name_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateName;
    document.getElementById("devicetpl_description_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateDescription;
    document.getElementById("devicetpl_navi_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateNavi;

    return true;
}

function editDeviceTemplateSubmit() {
    document.getElementById("devicetpl_edit_form").submit();
}

function editDeviceTemplate(devicetemplateid) {
    //Check input
    if(!devicetemplateid) { return false; }

    //Remove form from old field if not allready
    if(lastDeviceTemplateId) {
        document.getElementById("devicetpl_name_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateName;
        document.getElementById("devicetpl_description_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateDescription;
        document.getElementById("devicetpl_navi_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateNavi;
    }

    //Get elements
    var devicetpl_name = document.getElementById("devicetpl_name_"+devicetemplateid);
    var devicetpl_description = document.getElementById("devicetpl_description_"+devicetemplateid);
    var devicetpl_navi = document.getElementById("devicetpl_navi_"+devicetemplateid);

    //Get old field contents
    var old_name = devicetpl_name.innerHTML;
    var old_description = devicetpl_description.innerHTML;
    var old_navi = devicetpl_navi.innerHTML;

    //Set new lastDeviceTemplateId and co.
    lastDeviceTemplateId = devicetemplateid;
    lastDeviceTemplateName = old_name;
    lastDeviceTemplateDescription = old_description;
    lastDeviceTemplateNavi = old_navi;

    //Set current edit-id in form and set action-type to 1 (Edit)
    document.getElementById("devicetpl_edit_devicetemplateid").value = devicetemplateid;
    document.getElementById("devicetpl_edit_form_action").value = 1;

    //Insert form-fields into table and update navi
    devicetpl_name.innerHTML = '<input type="text" style="width: 200px;" name="devicetpl_edit_name" value=\''+old_name+'\' />';
    devicetpl_description.innerHTML = '<input type="text" style="width: 300px;" name="devicetpl_edit_description" value=\''+old_description+'\' />';
    devicetpl_navi.innerHTML = '<a href="#" onclick="editDeviceTemplateSubmit()" title="Save Changes"><img src="img/check.png"/></a>&nbsp;<a href="#" onclick="resetLastDeviceTemplateEdit()" title="Revert Changes"><img src="img/return.png"/></a>';

    return true;
}