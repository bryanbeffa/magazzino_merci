//delete articles popup
function deleteArticle(articlesId, articlesName) {
    var articlesName = decodeURIComponent(articlesName);

    document.getElementById("articleToDeleteId").value = articlesId;
    document.getElementById("deleteMessage").innerHTML = "Sei sicuro di voler eliminare l'articolo <b>" + articlesName + "</b>?";
}
