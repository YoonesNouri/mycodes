$(function () {
 	Highcharts.setOptions({
 		global: {
			useUTC: false
		}
	});
    if ($('#chart').length>0) $('#chart').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: null
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            }
        },
        yAxis: {
            title: {
                text: 'قیمت به ریال'
            }
        },
        tooltip: {
            formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                    Highcharts.dateFormat('%e. %b - %H:%M', this.x) +': '+ this.y;
            }
        },
        series: [
            {
                name: 'فروش دلار',
                data: $('#chart').data('dollar')
            }
        ],
        credits: {
  			enabled: false
			}
    });
    $('.contact-form').unbind('submit');
    $('.contact-form').submit(function(e){
        $.post('contact.php',$(this).serialize(),function(data){
            $("#contact_response").html(data);
        });
        e.preventDefault();
    });
    $(".analog").each(function(){
        $(this).clock({offset: $(this).data('timezone'), type: "analog"});
    });
    $('.flexslider').flexslider({
        animation: "slide"
    });
    
});
