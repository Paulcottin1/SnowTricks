$(document).ready(function(){
    var pageNb = 3;
    $("#loadbtn").click(function(e){
        e.preventDefault();
        $.ajax({
            url : "/load",
            type : "GET",
            dataType : "html",
            data : {
                "page": pageNb,
            },
            success: function loadTricks(li) {
                var div = document.getElementById("ul-tricks");
                div.insertAdjacentHTML("beforeend", li);
                pageNb = pageNb + 1;
            },
            error: function loadTricks() {
                alert("Impossible de charger des tricks");
            }
        });
    });
});
