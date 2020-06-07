function confirmOrder(articleId, articleName, articleCategory, articleQuantity, articleExpireDate, articleAvailableDate) {
    var articleName = decodeURIComponent(articleName);
    var articleCategory = decodeURIComponent(articleCategory);
    var articleQuantity = decodeURIComponent(articleQuantity);
    var articleExpireDate = decodeURIComponent(articleExpireDate);
    var articleAvailableDate = decodeURIComponent(articleAvailableDate);

    document.getElementById("articleId").value = articleId;
    document.getElementById("articleName").innerHTML = articleName;
    document.getElementById("articleCategory").innerHTML = articleCategory;
    document.getElementById("articleQuantity").innerHTML = articleQuantity;
    document.getElementById("articleExpireDate").innerHTML = articleExpireDate;
    document.getElementById("articleAvailableDate").innerHTML = articleAvailableDate;
}

function userConfirmOrder(articleId, articleName, articleCategory, articleQuantity, articleExpireDate, articleAvailableDate) {
    var articleName = decodeURIComponent(articleName);
    var articleCategory = decodeURIComponent(articleCategory);
    var articleQuantity = decodeURIComponent(articleQuantity);
    var articleExpireDate = decodeURIComponent(articleExpireDate);
    var articleAvailableDate = decodeURIComponent(articleAvailableDate);
    var articleUserQuantity = decodeURIComponent(articleUserQuantity);

    //delete errorMsg
    document.getElementById('deliveryDateError').innerHTML = '';
    var deliveryDate = document.getElementById('deliveryDate');
    deliveryDate.value = '';
    deliveryDate.className = "form-control text-center";

    document.getElementById("articleId").value = articleId;
    document.getElementById("articleName").innerHTML = articleName;
    document.getElementById("articleCategory").innerHTML = articleCategory;
    document.getElementById("articleQuantity").innerHTML = articleQuantity;
    document.getElementById("articleExpireDate").innerHTML = articleExpireDate;
    document.getElementById("articleAvailableDate").innerHTML = articleAvailableDate;
    document.getElementById("articleUserQuantity").max = parseInt(articleQuantity);
}

function decreaseQuantity(event) {
    event.preventDefault();

    var articleUserQuantityItem = document.getElementById("articleUserQuantity");
    if (parseInt(articleUserQuantityItem.value) - 1 > 0) {
        articleUserQuantityItem.value = parseInt(articleUserQuantityItem.value) - 1;
    }
}

function increaseQuantity(event) {
    event.preventDefault();

    var articleUserQuantityItem = document.getElementById("articleUserQuantity");
    if (parseInt(articleUserQuantityItem.value) + 1 <= parseInt(articleUserQuantityItem.max)) {
        articleUserQuantityItem.value = parseInt(articleUserQuantityItem.value) + 1;
    }
}

function deleteUserOrderRequest(orderId, userId, articleId, user, userEmail, userPhoneNumber, userAddress, articleName, articleQuantity, articleExpireDate, orderDate, deliveryDate) {
    var userId = decodeURIComponent(userId);
    var orderId = decodeURIComponent(orderId);
    var articleId = decodeURIComponent(articleId);
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


    document.getElementById("userId").value = userId;
    document.getElementById("orderId").value = orderId;
    document.getElementById("articleId").value = articleId;
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

function acceptUserOrderRequest(orderId, userId, articleId, user, userEmail, userPhoneNumber, userAddress, articleName, articleQuantity, articleExpireDate, orderDate, deliveryDate) {
    var userId = decodeURIComponent(userId);
    var orderId = decodeURIComponent(orderId);
    var articleId = decodeURIComponent(articleId);
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

    document.getElementById("acceptUserId").value = userId;
    document.getElementById("acceptOrderId").value = orderId;
    document.getElementById("acceptArticleId").value = articleId;
    document.getElementById("acceptUser").innerHTML = user;
    document.getElementById("acceptUserEmail").innerHTML = userEmail;
    document.getElementById("acceptUserPhoneNumber").innerHTML = userPhoneNumber;
    document.getElementById("acceptUserAddress").innerHTML = userAddress;
    document.getElementById("acceptArticleName").innerHTML = articleName;
    document.getElementById("acceptArticleQuantity").innerHTML = articleQuantity;
    document.getElementById("acceptArticleExpireDate").innerHTML = articleExpireDate;
    document.getElementById("acceptOrderDate").innerHTML = orderDateFormat;
    document.getElementById("acceptDeliveryDate").innerHTML = deliveryDateFormat;
}

function showOrderDetailHistory(articleName, orderQuantity, articleExpireDate, orderDate, decision, deliveryDate) {
    var articleName = decodeURIComponent(articleName);
    var orderQuantity = decodeURIComponent(orderQuantity);
    var articleExpireDate = decodeURIComponent(articleExpireDate);
    var orderDate = decodeURIComponent(orderDate);
    var deliveryDate = decodeURIComponent(deliveryDate);
    deliveryDate = new Date(deliveryDate);
    var deliveryDateFormat = deliveryDate.getDate().toString().padStart(2, 0) + '.' + (deliveryDate.getMonth() + 1).toString().padStart(2, 0) + '.' + deliveryDate.getFullYear();

    var decision = decodeURIComponent(decision);

    //order decision
    if (decision.trim() != '') {
        if (decision == 1) {
            decision = 'Accettato';
        } else {
            decision = 'Rifiutato';
        }
    } else {
        decision = 'Non ancora elaborato';
    }

    orderDate = new Date(orderDate);
    var orderDateFormat = orderDate.getDate().toString().padStart(2, 0) + '.' + (orderDate.getMonth() + 1).toString().padStart(2, 0) + '.' + orderDate.getFullYear() + ' ' + orderDate.getHours().toString().padStart(2, 0) + ':' + orderDate.getMinutes().toString().padStart(2, 0);

    document.getElementById("articleName").innerHTML = articleName;
    document.getElementById("orderQuantity").innerHTML = orderQuantity;
    document.getElementById("articleExpireDate").innerHTML = articleExpireDate;
    document.getElementById("orderDate").innerHTML = orderDateFormat;
    document.getElementById("deliveryDate").innerHTML = deliveryDateFormat;
    document.getElementById("orderDecision").innerHTML = decision;
}

//change filter button text
$(document).ready(function () {
    $("#filterToggleButton").click(function () {
        if ($('#filterLabel').text() == "Nascondi filtri") {
            $('#filterLabel').text("Mostra filtri");
            $('#filterImage').attr('src', '/magazzino_merci/application/libs/img/svg/down-arrow.svg');
        } else {
            $('#filterLabel').text("Nascondi filtri");
            $('#filterImage').attr('src', '/magazzino_merci/application/libs/img/svg/up-arrow.svg');
        }
        ;
    });

});