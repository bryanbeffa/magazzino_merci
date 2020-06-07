var lastNotification = '';
var firstTime = true;

function loadNotifications(httpRequest, url) {
    url = decodeURIComponent(url);
    httpRequest = decodeURIComponent(httpRequest);

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function (data) {
        if (this.readyState == 4 && this.status == 200) {
            var notifications = JSON.parse(this.responseText);

            var lastNotificationId = '';
            var userId;
            var mainDiv = document.getElementById("notificationsDiv");
            mainDiv.innerHTML = '';

            for (var i = 0; i < notifications.length; i++) {

                //set user id
                userId = notifications[i].utente_richiedente;

                var div = document.createElement("div");
                div.classList.add('dropdown-item');
                div.classList.add('border-bottom');
                div.classList.add('view');
                div.classList.add('overlay');

                //set last notification id
                if(i == 0){
                    lastNotificationId = "id" + notifications[i].id;
                    div.setAttribute('id', lastNotificationId);
                }

                //calculate time
                var diff = Math.abs(new Date(notifications[i].data) - new Date());

                //calc minutes
                var timeToDisplay = Math.floor((diff / 1000) / 60);
                var unit = 'm fa';

                //check if the difference is more than 59 minutes
                if (timeToDisplay > 59) {
                    timeToDisplay = Math.floor((diff / 1000) / 60 / 60);
                    unit = 'h fa';

                    //check if the difference is more than 23 hours
                    if (timeToDisplay > 23) {
                        var date = new Date(notifications[i].data);
                        timeToDisplay = date.getDate().toString().padStart(2, 0) + '.' + (date.getMonth() + 1).toString().padStart(2, 0) + '.' + date.getFullYear().toString().padStart(2, 0) + ' ' + date.getHours().toString().padStart(2, 0) + ':' + date.getMinutes().toString().padStart(2, 0);
                        unit = '';
                    }
                }

                //add time
                var time = document.createElement("sub");
                time.innerHTML = timeToDisplay + unit;
                time.classList.add("mb-2");
                time.classList.add("mt-2");
                time.classList.add("text-right");
                time.classList.add("d-block");

                //add description
                var notificationDescription = document.createElement("p");
                notificationDescription.classList.add("m-0");

                //add empty div hover effect
                var effectDiv = document.createElement("div");
                effectDiv.classList.add("mask");

                if (notifications[i].id_tipo_operazione == 1) {
                    div.classList.add('deep-orange');
                    div.classList.add('lighten-4');
                    notificationDescription.innerHTML = "Il tuo ordine è stato rifiutato";
                    effectDiv.classList.add("rgba-red-light");
                } else if (notifications[i].id_tipo_operazione == 2) {
                    div.classList.add('green');
                    div.classList.add('accent-1');
                    notificationDescription.innerHTML = "Il tuo ordine è stato accettato";
                    effectDiv.classList.add("rgba-green-light");
                }

                //add title
                var notificationTitle = document.createElement("b");
                notificationTitle.innerHTML = notifications[i].nome_articolo;

                //append child
                div.append(time);
                div.append(notificationTitle);
                div.append(notificationDescription);
                div.append(effectDiv);
                mainDiv.append(div);
            }

            if(lastNotificationId != lastNotification){
                if(!firstTime){
                    //add animation
                    var img = document.getElementById('notificationImg');
                    img.classList.add('animated');
                    img.classList.add('tada');
                    img.classList.add('infinite');
                    img.src = '/magazzino_merci/application/libs/img/svg/bell_active.svg';
                } else {
                    notificationChecked();
                    firstTime = false;
                }
            }

            //add link div
            var linkDiv = document.createElement("div");
            linkDiv.classList.add('mt-2');

            //add link
            var link = document.createElement("a");
            link.classList.add('text-primary');
            link.classList.add('d-block');
            link.href = url;
            link.innerHTML = "Visualizza tutte le notifiche";
            linkDiv.append(link);

            mainDiv.append(linkDiv);
        }
    };
    xhttp.open("POST", httpRequest, true);
    xhttp.send();
}

function notificationChecked(){
    //remove animation
    var img = document.getElementById('notificationImg');
    img.src = '/magazzino_merci/application/libs/img/svg/bell.svg';
    img.classList.remove('animated');
    img.classList.remove('tada');
    img.classList.remove('infinite');

    var lastId = document.getElementById("notificationsDiv").firstChild.id;
    lastNotification = lastId;
}

function showOrderDetail(orderId, acceptedOrder, articleName, articleQuantity, articleExpireDate, orderDate, deliveryDate) {

    var orderId = decodeURIComponent(orderId);
    var acceptedOrder = decodeURIComponent(acceptedOrder);
    var articleName = decodeURIComponent(articleName);
    var articleQuantity = decodeURIComponent(articleQuantity);
    var articleExpireDate = decodeURIComponent(articleExpireDate);
    var deliveryDate = decodeURIComponent(deliveryDate);
    deliveryDate = new Date(deliveryDate);
    var deliveryDateFormat = deliveryDate.getDate().toString().padStart(2, 0) + '.' + (deliveryDate.getMonth() + 1).toString().padStart(2, 0) + '.' + deliveryDate.getFullYear();

    var orderDate = decodeURIComponent(orderDate);
    orderDate = new Date(orderDate);
    var orderDateFormat = orderDate.getDate().toString().padStart(2, 0) + '.' + (orderDate.getMonth() + 1).toString().padStart(2, 0) + '.' + orderDate.getFullYear() + ' ' + orderDate.getHours().toString().padStart(2, 0) + ':' + orderDate.getMinutes().toString().padStart(2, 0) + ':' + orderDate.getSeconds().toString().padStart(2, 0);

    var msg;
    if (acceptedOrder == 1) {
        msg = 'Questo ordine è stato accettato';
    } else {
        msg = 'Questo ordine è stato rifiutato';
    }


    document.getElementById("orderId").innerHTML = orderId;
    document.getElementById("infoMessage").innerHTML = msg;
    document.getElementById("articleName").innerHTML = articleName;
    document.getElementById("articleQuantity").innerHTML = articleQuantity;
    document.getElementById("articleExpireDate").innerHTML = articleExpireDate;
    document.getElementById("deliveryDate").innerHTML = deliveryDateFormat;
    document.getElementById("orderDate").innerHTML = orderDateFormat;
}