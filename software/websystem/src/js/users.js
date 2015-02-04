/* This file contains javascript-functions used on users-page */

function despawnNewUserForm() {
    //Set display to none
    document.getElementById("newUserForm_wrapper").style.display = "none";

    return true;
}

function spawnNewUserForm() {
    //Despawn other form if still visible
    despawnNewUserForm();

    //Display wrapper
    document.getElementById("newUserForm_wrapper").style.display = "inline-block";

    return true;
}