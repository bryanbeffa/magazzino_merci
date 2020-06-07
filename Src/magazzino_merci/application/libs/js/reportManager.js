
function showOrderRequestDetails(user, userEmail, userPhoneNumber, userAddress, articleName, articleQuantity, articleExpireDate, orderDate, deliveryDate) {

    var user = decodeURIComponent(user);
    var userEmail = decodeURIComponent(userEmail);
    var userPhoneNumber = decodeURIComponent(userPhoneNumber);
    var userAddress = decodeURIComponent(userAddress);
    var articleName = decodeURIComponent(articleName);
    var articleQuantity = decodeURIComponent(articleQuantity);
    var articleExpireDate = decodeURIComponent(articleExpireDate);
    var orderDate = decodeURIComponent(orderDate);
    orderDate = new Date(orderDate);
    var orderDateFormat = orderDate.getDate().toString().padStart(2, 0) + '.' + (orderDate.getMonth() + 1).toString().padStart(2, 0) + '.' + orderDate.getFullYear() + ' ' + orderDate.getHours().toString().padStart(2, 0) + ':' + orderDate.getMinutes().toString().padStart(2, 0) + ':' + orderDate.getSeconds().toString().padStart(2, 0);
    var deliveryDate = decodeURIComponent(deliveryDate);
    deliveryDate = new Date(deliveryDate);
    var deliveryDateFormat = deliveryDate.getDate().toString().padStart(2, 0) + '.' + (deliveryDate.getMonth() + 1).toString().padStart(2, 0) + '.' + deliveryDate.getFullYear();

    document.getElementById("user").innerHTML = user;
    document.getElementById("userEmail").innerHTML = userEmail;
    document.getElementById("userPhoneNumber").innerHTML = userPhoneNumber;
    document.getElementById("userAddress").innerHTML = userAddress;
    document.getElementById("articleName").innerHTML = articleName;
    document.getElementById("articleQuantity").innerHTML = articleQuantity;
    document.getElementById("articleExpireDate").innerHTML = articleExpireDate;
    document.getElementById("orderDate").innerHTML = orderDateFormat;
    document.getElementById("deliveryDate").innerHTML = deliveryDateFormat;
}

function showArticleDetails(articleId, articleName, articleQuantity, articleExpireDate, articleAvailableDate, operation, status){
    var articleId = decodeURIComponent(articleId);
    var articleName = decodeURIComponent(articleName);
    var articleQuantity = decodeURIComponent(articleQuantity);
    var articleExpireDate = decodeURIComponent(articleExpireDate);
    var operation = decodeURIComponent(operation);
    var articleAvailableDate = decodeURIComponent(articleAvailableDate);

    document.getElementById("articleId").innerHTML = articleId;
    document.getElementById("articleNameDetail").innerHTML = articleName;
    document.getElementById("articleQuantityDetail").innerHTML = articleQuantity;
    document.getElementById("articleExpireDateDetail").innerHTML = articleExpireDate;
    document.getElementById("articleAvailableDateDetail").innerHTML = articleAvailableDate;
    document.getElementById("operation").innerHTML = operation;
    document.getElementById("articleStatus").innerHTML = status;
}
