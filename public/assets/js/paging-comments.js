$(document).ready(function(){
    var pageNb = 2;
    var trickId = $("#trick-page").data("trick");
    $("#loadComments").click(function(e){
        e.preventDefault();
        $.ajax({
            url : "/loadComments",
            type :"GET",
            dataType : "html",
            data : {
                "page": pageNb,
                "trick": trickId,
            },
            success: function loadComments(li) {
                var div = document.getElementById("comments");
                div.insertAdjacentHTML("beforeend", li);
                pageNb = pageNb + 1;
            },
            error: function loadComments() {
                alert("Impossible de charger plus de commentaires");
            }
        });
    });
});
