function daysPopulate(){
    var days = document.getElementById("days");
    for(var i = 1; i <= 31; i++){
        var newDay = document.createElement("option");
        newDay.innerHTML = i.toString();
        days.options.add(newDay);
    }
};

$(document).ready(function(){
    daysPopulate();
    $("#peculiar").click(function(){
        $("#birthDeets").toggle("slow");
    });

    $("#data_sets").change(function(){
        $("#date_entry").animate({height:'show'}, "show");
        $("#button").animate({height:'show'}, "show");
    });
});

function reportBrokenVideo(song_id, ip){
    $.get("/broken.php", {
        id: song_id,
        ip: ip
    }, function(response){
        $("#report").html("issue reported!");
    });
};

function populate(list_select, year_select){
    var currentYear = new Date().getFullYear();
    var list = document.getElementById(list_select);
    var year = document.getElementById(year_select);
    year.innerHTML = "";
    if(list.value == "track"){ minYear = 1959;
    } else if(list.value == "country") { minYear = 1945;
    } else if(list.value == "latin") { minYear = 1987; }
        for(var i = minYear; i <= currentYear; i++){
            var newOption = document.createElement("option");
            newOption.innerHTML = i.toString();
            year.options.add(newOption);
        }
};
