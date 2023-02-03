$("#file").change(function () {
    filePreview(this.files);
});

var remaining = document.getElementById('remaining');
var submit = document.getElementById('submit');
var count = 3;
var row_id = 1;


function upload_btn() {
    var file_upload = document.getElementById("file");
    file_upload.click();
}


function filePreview(input) {
    console.log(input);
    if (input && input[0] && count) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#uploaded').append(
                '<div class="row my-2" id="row' + row_id + '"><div class="col py-1 align-self-center" id="preview' + row_id + '"></div><div class="col align-self-center" id="name' + row_id + '"></div><div class="col text-center align-self-center" id="remove' + row_id + '"></div></div>'
            )
            $('#preview' + row_id).append(
                '<img src="' + e.target.result + '" height="50"/>'
            );

            $('#name' + row_id).append(
                '<span type="button" class="text-success text-center">' + input[0].name + '</span>'
            );

            $('#remove' + row_id).append(
                '<span type="button" class="text-danger text-center" onclick="remove_image(' + row_id + ')">Remove</span>'
            );
            count -= 1;
            row_id++;
            $('#count').text(count);
            if (!count) {
                if (!remaining.classList.contains('d-none')) {
                    remaining.classList.toggle("d-none");
                }
                if (submit.classList.contains('d-none')) {
                    submit.classList.toggle("d-none");
                }
            }
        };
        reader.readAsDataURL(input[0]);
    }
}

function remove_image(id) {
    var row = document.getElementById("row" + id);
    row.remove();
    count += 1;
    $('#count').text(count);
    if (count) {
        if (remaining.classList.contains('d-none')) {
            remaining.classList.toggle("d-none");
        }
        if (!submit.classList.contains('d-none')) {
            submit.classList.toggle("d-none");
        }
    }
}


window.addEventListener('paste', e => {
    const fileInput = document.getElementById("file");
    filePreview(e.clipboardData.files);
});