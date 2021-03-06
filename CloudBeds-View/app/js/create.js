$(document).ready(function() {

    const url = window.location.origin;
    const apiUrl = 'http://ec2-54-188-222-158.us-west-2.compute.amazonaws.com';

    $( "#createIntervalForm" ).submit(function( event ) {

        var bodyFormData = new FormData();
        bodyFormData.set('start_date', $('#startDate').val());
        bodyFormData.set('end_date', $('#endDate').val());
        bodyFormData.set('price', $('#price').val());

        axios({
            method: 'post',
            url: apiUrl + '/api/intervals/save',
            data: bodyFormData,
            config: { headers: {'Content-Type': 'multipart/form-data' }}
        }).then(function (response) {
            window.location.replace(url + "/app/views/list.html");
        }).catch(function (response) {
            window.alert(response);
        });

        event.preventDefault();
    });

} );