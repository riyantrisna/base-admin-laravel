function jquery_ajax_error(jqXHR, exception){
    var msg = '';

    if (jqXHR.status === 0)
    {
        msg = 'Not connect.\n Verify Network.';
    }
    else if (jqXHR.status == 404)
    {
        msg = 'Requested page not found';
    }
    else if (jqXHR.status == 500)
    {
        msg = 'Internal Server Error';
    }
    else if (exception === 'parsererror')
    {
        msg = 'Requested JSON parse failed.';
    }
    else if (exception === 'timeout')
    {
        msg = 'Time out error.';
    }
    else if (exception === 'abort')
    {
        msg = 'Request aborted.';
    }
    else
    {
        msg = 'Uncaught Error.\n' + jqXHR.responseText;
    }

    return msg
}
