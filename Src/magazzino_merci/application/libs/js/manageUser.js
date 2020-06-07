//delete user popup
function deleteUser(userId, userName) {
    var userName = decodeURIComponent(userName);

    document.getElementById("userToDeleteId").value = userId;
    document.getElementById("deleteMessage").innerHTML = "Sei sicuro di voler eliminare l'utente <b>" + userName + "</b>?";
}
