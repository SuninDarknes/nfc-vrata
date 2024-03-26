dragula([
    document.getElementById('1'),
    document.getElementById('2')
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

            } else if (el.parentNode.id == "1" && input.name[1] == "p") input.name = input.name.substring(1);
            else if (el.parentNode.id == "2" && input.name[1] == "d") input.name = input.name.substring(1);

            window.setTimeout(function () {
                el.classList.remove('is-moved');
            }, 600);
        }, 100);
    });

 document.querySelectorAll(".drag-item").forEach(function (el) {
    el.onclick = function () {
        if (el.parentNode.id == "1") {
            document.getElementById('2').appendChild(el);
        } else {
            document.getElementById('1').appendChild(el);
        }
        let input = el.querySelector("input");
        if (input.name[0] != "u") {
            if (input.name[0] == "p" && el.parentNode.id == "2") input.name = "u" + input.name;
            else if (input.name[0] == "d" && el.parentNode.id == "1") input.name = "u" + input.name;

        } else if (el.parentNode.id == "1" && input.name[1] == "p") input.name = input.name.substring(1);
        else if (el.parentNode.id == "2" && input.name[1] == "d") input.name = input.name.substring(1);
        el.classList.add('is-moved');
        window.setTimeout(function () {
            el.classList.remove('is-moved');
        }, 600);
    };
 });