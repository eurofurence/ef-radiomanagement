/* This file contains javascript-functions used on users-page */

/* despawnNewUserForm()
 *
 * This function despawns the newUserForm
 */
function despawnNewUserForm() {
    //Set display to none
    document.getElementById("newUserForm_wrapper").style.display = "none";

    return true;
}

/* spawnNewUserForm()
 *
 * This function spawns the newUserForm
 */
function spawnNewUserForm() {
    //Despawn other form if still visible
    despawnNewUserForm();

    //Display wrapper
    document.getElementById("newUserForm_wrapper").style.display = "inline-block";

    return true;
}

/* deleteUser()
 *
 * This function submits the deleteUserForm
 */
function deleteUser(userid) {
    //Check input
    if(!userid || userid<1) { return false; }

    if(confirm("Do you realy want to delete the user with the UID "+userid+" and ALL ASSOCIATED BINDINGS?")) {
        //Set input and submit form
        document.getElementById("deleteUserForm_userid").value=userid;
        document.getElementById("deleteUserForm").submit();

        return true;
    }

    return false;
}
