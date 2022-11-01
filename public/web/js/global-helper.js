let createDateTime = function () {
    let dateTimePicker = $('.date-time-picker');
    dateTimePicker.flatpickr({
        enableTime: false,
        minDate: "today",
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
    });
}

// Tags. For more info, please visit the official plugin site: https://yaireo.github.io/tagify/
let createTags = function () {
    let tags = new Tagify(document.querySelector('[name="tags"]'), {
        whitelist: [],
        maxTags: 5,
        dropdown: {
            maxItems: 10,           // <- mixumum allowed rendered suggestions
            enabled: 0,             // <- show suggestions on focus
            closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
        }
    });
    tags.on("change", function(){
        // Revalidate the field when an option is chosen
        validator.revalidateField('tags');
    });
}

let createSelect2 = function () {
    // Check if jQuery included
    if (typeof jQuery == 'undefined') {
        return;
    }

    // Check if select2 included
    if (typeof $.fn.select2 === 'undefined') {
        return;
    }

    let elements = [].slice.call(document.querySelectorAll('[data-control="select2"], [data-kt-select2="true"]'));

    elements.map(function (element) {
        if (element.getAttribute("data-kt-initialized") === "1") {
            return;
        }

        let options = {
            dir: document.body.getAttribute('direction')
        };

        if (element.getAttribute('data-hide-search') === 'true') {
            options.minimumResultsForSearch = Infinity;
        }

        $(element).select2(options);

        element.setAttribute("data-kt-initialized", "1");
    });
};

let createTagify = function (inputElm) {

    // initialize Tagify on the above input node reference
    var tagify = new Tagify(inputElm, {
        enforceWhitelist: true,
        whitelist: inputElm.value.trim().split(/\s*,\s*/) // Array of values. stackoverflow.com/a/43375571/104380
    })

    // Chainable event listeners
    tagify.on('add', onAddTag)
        .on('remove', onRemoveTag)
        .on('input', onInput)
        .on('edit', onTagEdit)
        .on('invalid', onInvalidTag)
        .on('click', onTagClick)
        .on('focus', onTagifyFocusBlur)
        .on('blur', onTagifyFocusBlur)
        .on('dropdown:hide dropdown:show', e => console.log(e.type))
        .on('dropdown:select', onDropdownSelect)

    var mockAjax = (function mockAjax(){
        var timeout;
        return function(duration){
            clearTimeout(timeout); // abort last request
            return new Promise(function(resolve, reject){
                timeout = setTimeout(resolve, duration || 700, whitelist)
            })
        }
    })()

    // tag added callback
    function onAddTag(e){
        tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
    }

    // tag remvoed callback
    function onRemoveTag(e){
    }

    // on character(s) added/removed (user is typing/deleting)
    function onInput(e){
        tagify.whitelist = null; // reset current whitelist
        tagify.loading(true) // show the loader animation

        // get new whitelist from a delayed mocked request (Promise)
        mockAjax()
            .then(function(result){
                tagify.settings.whitelist = result.concat(tagify.value) // add already-existing tags to the new whitelist array

                tagify
                    .loading(false)
                    // render the suggestions dropdown.
                    .dropdown.show(e.detail.value);
            })
            .catch(err => tagify.dropdown.hide())
    }

    function onTagEdit(e){
    }

    // invalid tag added callback
    function onInvalidTag(e){
    }

    // invalid tag added callback
    function onTagClick(e){
    }

    function onTagifyFocusBlur(e){
    }

    function onDropdownSelect(e){
    }
}

/* begin:: render fragment content */
$('.fragment').click(function () {
    let fragmentName = $(this).attr("data-fragment");
    let formRenderer = $('#form-renderer');
    let inputFragment = $('.input-fragment');
    inputFragment.val(fragmentName);
    formRenderer.submit();
});
/* end:: render fragment content */
