/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2016 Niels Gandraß <ngandrass@squacu.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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
