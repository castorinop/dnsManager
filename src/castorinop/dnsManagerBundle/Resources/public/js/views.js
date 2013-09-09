//Get the ul that holds the collection of views
var collectionHolder = $('ul.views');

// setup an "add a view" link
var $addViewLink = $('<a href="#" class="add_view_link">Add a view</a>');
var $newLinkLi = $('<li></li>').append($addViewLink);

jQuery(document).ready(function() {
    // add a delete link to all of the existing tag form li elements
    collectionHolder.find('li').each(function() {
        addViewFormDeleteLink($(this));
    });

    // add the "add a view" anchor and li to the views ul
    collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    collectionHolder.data('index', collectionHolder.find(':input').length);

    $addViewLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new view form (see next code block)
        addViewForm(collectionHolder, $newLinkLi);
    });
});

function addViewForm(collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = collectionHolder.data('prototype');

    // get the new index
    var index = collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a view" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    
    addViewFormDeleteLink($newFormLi);
    
}	

function addViewFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#"><i class="icon-remove"></i></a>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}
