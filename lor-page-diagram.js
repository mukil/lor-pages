
function update_diagram (target, as_of, lor_id) {

    var year_string = ""

    if (as_of == 201312) {
        year_string = "2013/12"
    } else if (as_of == 201306) {
        year_string = "2013/06"
    } else if (as_of == 201406) {
        year_string = "2014/06"
    } else if (as_of == 201206) {
        year_string = "2012/06"
    } else if (as_of == 201212) {
        year_string = "2012/12"
    } else if (as_of == 201112) {
        year_string = "2011/12"
    }

    $('.diagram-layer-controls span').removeClass('selected')
    $(target).addClass('selected')

    var url = "/~malte/"+year_string+"/calculation.php?lor=" + lor_id

    $.get(url, function (data) {
        var result = JSON.parse(data)
        var new_line = []
        for (var idx in result.data) {
            var element = result.data[idx]
            new_line.push(element.lor_percentage.substr(0, element.lor_percentage.indexOf("%")))
        }
        redraw_diagram(new_line)
    })

    function redraw_diagram(new_line) {
        // gui update
        $('.berlin-bar').attr('style', 'background-color: rgba(124,99,124,0.3);')
        $('#aging-chart').empty()
        // data update
        var ages = [1,2,3,5,6,7,8,10,12,14,15,18,21,25,27,30,35,40,45,50,55,60,63,65,67,70,75,80,85,90,95,110];
        var r = Raphael("aging-chart"), txtattr = { font: "14px sans-serif" };
        var chart = r.linechart(15, 30, 700, 270,
            [ages], [window.lor_current_percentages, new_line, window.berlin_percentages],
            {smooth: true, axis: "0 0 1 1", symbol: "circle", axisxstep: 100, colors: ["#2f69bf", "#bf5a2f", "rgba(124,99,124,0.3)"] });
        chart.symbols.attr({ r: 5 });
    }

}
