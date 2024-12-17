function sendAjaxRequest(url, id, action, token) {
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            currentServiceId: id,
            currentAction: action,
            _token: token
        },
        success: function(response) {
            alert(response.message);
            table.ajax.reload();
        },
        error: function(xhr, status, error) {
            var response = xhr.responseJSON;
                alert(response.message);
        }
    });
}

