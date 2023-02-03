$("#file").change(function () {
    filePreview(this.files);
});


var count = 0;


function upload_btn() {
    var file_upload = document.getElementById("file");
    file_upload.click();
}


function filePreview(input) {
    console.log(input);
    if (input && input[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            count += 1;
            // $('#uploadForm + img').remove();
            // $('#uploadForm').after('<img src="'+e.target.result+'" width="450" height="300"/>');
            $('#uploaded').append(
                '<div class="row my-2" id="row' + count + '"><div class="col" id="preview' + count + '"></div><div class="col text-center align-self-center" id="remove' + count + '"></div></div>'
            )
            $('#preview' + count).append(
                '<img src="' + e.target.result + '" height="50"/>'
            );

            $('#remove' + count).append(
                '<span type="button" class="text-danger text-center" onclick="remove_image(' + count + ')">Remove</span>'
            );
        };
        reader.readAsDataURL(input[0]);
    }
}

function remove_image(id) {
    var row = document.getElementById("row" + id);
    row.remove();
    count -= 1;
}


window.addEventListener('paste', e => {
    const fileInput = document.getElementById("file");
    filePreview(e.clipboardData.files);
});