$(document).ready(function(){
    var page = 2;
    var trick = $("#trick-page").data('trick');
    $("#loadComments").click(function(e){
        e.preventDefault();
        $.ajax({
            url : '/loadComments',
            type :'GET',
            dataType : 'html',
            data : {
                'page': page,
                'trick': trick,
            },
            success: function(data) {
                var div = document.getElementById('comments');
                div.insertAdjacentHTML('beforeend', data);
                page = page + 1;
            },
            error:function(data) {
                alert('Impossible de charger plus de commentaires');
            }
        });
    });
});
