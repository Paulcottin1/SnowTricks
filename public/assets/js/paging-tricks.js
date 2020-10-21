$(document).ready(function(){
    var page = 3;
    $("#loadbtn").click(function(e){
        e.preventDefault();
        $.ajax({
            url : '/load',
            type :'GET',
            dataType : 'html',
            data : {
                'page': page,
            },
            success: function(data) {
                var div = document.getElementById('ul-tricks');
                div.insertAdjacentHTML('beforeend', data);
                page = page + 1;
            },
            error:function(data) {
                alert('Impossible de charger des tricks');
            }
        });
    });
});
