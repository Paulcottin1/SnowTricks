// Show/hidden trick media for small screen
$(function () {
    $("#loadMedia").on("click", function (e) {
        e.preventDefault();
        $("div.medias").removeClass("d-none");
        $("#loadMedia").addClass("d-none");
        $("#hideMedia").removeClass("d-none");
    });
    $("#hideMedia").on("click", function (e) {
        e.preventDefault();
        $("div.medias").addClass("d-none");
        $("#loadMedia").removeClass("d-none");
        $("#hideMedia").addClass("d-none");
    });
});

// Upload multiple for images and videos
var $collectionHolder;

// setup an "add a tag" link
var $addTagButton = $('<button type="button" class="add_tag_link btn btn-green padding-left padding-right margin-top"> Ajouter une photo</button>');
var $newLinkLi = $('<li></li>').append($addTagButton);

var $addVideoButton = $('<button type="button" class="add_tag_link btn btn-green padding-left padding-right margin-top"> Ajouter une vidéo </button>');
var $newVideoLi = $('<li></li>').append($addVideoButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.image');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // Get the ul that holds the collection of video
    $collectionHolderVideo = $('ul.video');

    // add the "add a video" anchor and li to the videos ul
    $collectionHolderVideo.append($newVideoLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addTagButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });

    $addVideoButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addTagForm($collectionHolderVideo, $newVideoLi);
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class="margin-top"></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}
