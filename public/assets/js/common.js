function ajaxStartStop() {
    $(document).ajaxStart(function () {
        $('#preloader').show();
    });

    $(document).ajaxStop(function () {
        $('#preloader').hide();
    });
}

function isNull(value) {
    if(value === null && typeof value === "object") {
        return '';
    }

    return value;
}

function notificationMsg(msg, type) {
    if (type == 'error' || type == false) {
        var toaddClass = 'alert alert-danger';
    } else {
        var toaddClass = 'alert alert-success';
    }
    $html = '<div class="'+toaddClass+'"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>\n' +
        '<strong></strong> ' + msg + '</div>';
    $('#notificationMessage').html('');
    $('#notificationMessage').html($html);
    $('#notificationMessage').show();
    // $('#notificationMessage').fadeOut(3000);
}

function arrDifference (a1, a2) {

    var a = [], diff = [];

    for (var i = 0; i < a1.length; i++) {
        a[a1[i]] = true;
    }

    for (var i = 0; i < a2.length; i++) {
        if (a[a2[i]]) {
            delete a[a2[i]];
        } else {
            a[a2[i]] = true;
        }
    }

    for (var k in a) {
        diff.push(k);
    }

    return diff;
}