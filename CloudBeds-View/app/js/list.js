

$(document).ready(function() {

    const url = window.location.origin;
    const apiUrl = 'ec2-54-188-222-158.us-west-2.compute.amazonaws.com';

    axios.get('http://localhost/api/intervals/list')

        .then(function (intervals) {
            $.each( intervals, function( index, interval ){
                $.each( interval.response, function( index, value ){
                    productsAdd(value.start_date, value.end_date, value.price, value.id);
                });
            });

        }).catch(function (error) {

        })

        .finally(function () {
        });

    $(document).on("click","a.btn-danger", function() {

        axios.get(apiUrl + '/api/intervals/delete/'+ $('tbody > tr > td >  a.btn-danger').attr('name')).then(function (response) {
                window.location.replace(url + "/app/views/list.html");
            }).catch(function (error) {

            }).finally(function () {

            });
    });

    $('#migrate').click(function () {
        axios.get(apiUrl +'/migrations').then(function (response) {
            window.location.replace(url + "/app/views/list.html");
        }).catch(function (error) {

        }).finally(function () {

        });
    });

    function productsAdd(startDate, endDate, price, id) {

        $("#listIntervals tbody").append(
            "<tr>" +
            "<td>"+startDate+"</td>" +
            "<td>"+endDate+"</td>" +
            "<td>"+price+"</td>" +
            "<td class='align-middle'>" +
                "<a style='margin-left: 10%' id ='updateInterval' name='"+id+"' class='btn btn-warning' href='"+window.location.origin+"/CloudBeds-View/app/views/edit.html?id="+id+"' role='button'>Edit</a>"+
                "<a style='margin-left: 10%' id ='deleteInterval' name='"+id+"' class='btn btn-danger' href= '#' role='button'>Delete</a>"+
            "</td>"+
            "</tr>"
        );
    }

} );