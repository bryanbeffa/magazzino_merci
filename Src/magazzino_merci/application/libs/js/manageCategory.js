//delete category popup
function deleteCategory(categoryId, categoryName) {
    var categoryName = decodeURIComponent(categoryName);

    document.getElementById("categoryToDeleteId").value = categoryId;
    document.getElementById("deleteMessage").innerHTML = "Sei sicuro di voler eliminare la categoria <b>" + categoryName + "</b>?";
}
