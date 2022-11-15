$(document).ready(function ($) {
    $('.input input').keypress(function (e) {
        if (e.keyCode == 13 && $(this).val() != '') {
            var city_name = $(this).val();
            fetchData(city_name);
        }
    });

    $('.search').on('click', function (e) {
        var city_name = $('.input input').val();
        fetchData(city_name);
    });

    function fetchData(city_name) {
        $('.weather-data__data').hide();
        $('.weather-data__error').hide();
        $('.searching').addClass('shown');
        $('.results').addClass('shown');
        $('.cont').toggleClass('shift');

        var CSRF_TOKEN = $("meta[name='csrf-token']").attr('content');
        $.ajax({
            /* the route pointing to the post function */
            url: '/api/cities',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: { _token: CSRF_TOKEN, name: city_name },
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) {
                if (data.status == 'Success') {
                    var data = data.data.weather_data;
                    
                    $('.weather-data__data-values--temprature span').text(data.main.temp);
                    $('.weather-data__data-values--temprature-max-min span').text(data.main.temp_max + '/' + data.main.temp_min);
                    $('.weather-data__data-values--humidity span').text(data.main.humidity);
                    $('.weather-data__data-values--pressure span').text(data.main.pressure);
                    $('.weather-data__data-values--wind span').text(data.wind.speed);
                    $('.weather-data__data-values--visibility span').text(data.visibility);

                    $('.searching').removeClass('shown');
                    $('.cont').toggleClass('shift');

                    $('.weather-data__data').show();
                }
            },
            error: function (error) {
                $('.searching').removeClass('shown');
                $('.weather-data__data').hide();
                $('.weather-data__error').show();
                $('.weather-data__error').text(error.responseJSON.message);
            }
        });
    }
});