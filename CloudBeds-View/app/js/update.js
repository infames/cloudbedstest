$(document).ready(function() {

    const url = window.location.origin;
    const apiUrl = 'ec2-54-188-222-158.us-west-2.compute.amazonaws.com';

    id = getUrlParameter('id');

    axios.get('http://localhost/api/intervals/'+ id).then(function (response) {

        $('#endDate').text(response.data.response.end_date);
        $('#endDate').val(response.data.response.end_date);
        $('#startDate').text(response.data.response.start_date);
        $('#startDate').val(response.data.response.start_date);
        $('#price').text(response.data.response.price);
        $('#price').val(response.data.response.price);

    }).catch(function (error) {

    }).finally(function () {

    });

    $( "#updateIntervalForm" ).submit(function( event ) {

        var bodyFormData = new FormData();
        bodyFormData.set('start_date', $('#startDate').val());
        bodyFormData.set('end_date', $('#endDate').val());
        bodyFormData.set('price', $('#price').val());
        bodyFormData.set('id', id);

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

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };

} );