var gle;
dragula([
    document.getElementById('1'),
    document.getElementById('2'),
    document.getElementById('3'),
    document.getElementById('4'),
    document.getElementById('5')
])

    .on('drag', function (el) {

        el.classList.add('is-moving');
    })
    .on('dragend', function (el) {

        el.classList.remove('is-moving');


        window.setTimeout(function () {
            el.classList.add('is-moved');
            let input = el.querySelector("input");
            if (input.name[0] != "u") {
                if (input.name[0] == "p" && el.parentNode.id == "2") input.name = "u" + input.name;
                else if (input.name[0] == "d" && el.parentNode.id == "1") input.name = "u" + input.name;

            } else if (el.parentNode.id == "1" && input.name[1] == "p") input.name = input.name.substring(1) ;
            else if (el.parentNode.id == "2" && input.name[1] == "d") input.name = input.name.substring(1) ;

            window.setTimeout(function () {
                el.classList.remove('is-moved');
            }, 600);
        }, 100);
    });


var createOptions = (function () {
    var dragOptions = document.querySelectorAll('.drag-options');

    // these strings are used for the checkbox labels
    var options = ['Research', 'Strategy', 'Inspiration', 'Execution'];

    // create the checkbox and labels here, just to keep the html clean. append the <label> to '.drag-options'
    function create() {
        for (var i = 0; i < dragOptions.length; i++) {

            options.forEach(function (item) {
                var checkbox = document.createElement('input');
                var label = document.createElement('label');
                var span = document.createElement('span');
                checkbox.setAttribute('type', 'checkbox');
                span.innerHTML = item;
                label.appendChild(span);
                label.insertBefore(checkbox, label.firstChild);
                label.classList.add('drag-options-label');
                dragOptions[i].appendChild(label);
            });

        }
    }

    return {
        create: create
    }


}());

var showOptions = (function () {

    // the 3 dot icon
    var more = document.querySelectorAll('.drag-header-more');

    function show() {
        // show 'drag-options' div when the more icon is clicked
        var target = this.getAttribute('data-target');
        var options = document.getElementById(target);
        options.classList.toggle('active');
    }


    function init() {
        for (i = 0; i < more.length; i++) {
            more[i].addEventListener('click', show, false);
        }
    }

    return {
        init: init
    }
}());

createOptions.create();
showOptions.init();





