$("#file").change(function () {
    filePreview(this.files);
});

var remaining = document.getElementById('remaining');
var submit = document.getElementById('submit');
var count = 3;
var row_id = 1;
var userId = "";
var file_list = [];
var key = "";


function upload_btn() {
    var file_upload = document.getElementById("file");
    file_upload.click();
}


function filePreview(input) {
    if (input && input[0] && count) {
        file_list.push(input[0]);
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#uploaded').append(
                '<div class="col my-2" id="row' + row_id + '"><div class="py-2 row align-self-center" id="preview' + row_id + '"></div><div class="row align-self-center" id="name' + row_id + '"></div><div class="col text-center align-self-center" id="remove' + row_id + '"></div></div>'
            )
            $('#preview' + row_id).append(
                '<img src="' + e.target.result + '" height="250" />'
            );

            $('#remove' + row_id).append(
                '<span type="button" class="text-danger text-center mt-2" onclick="remove_image(' + row_id + ')"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16"><path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>  </svg></span>'
            );
            count -= 1;
            row_id++;
            $('#count').text(count);
            if (count) {

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
    if (count == 3) {

        if (!submit.classList.contains('d-none')) {
            submit.classList.toggle("d-none");
        }
    }
}

window.addEventListener('paste', e => {
    const fileInput = document.getElementById("file");
    filePreview(e.clipboardData.files);
});


if (!localStorage.getItem("session")) {
    var key = CryptoJS.MD5(document.cookie).toString();
    localStorage.setItem("session", key);
    document.cookie = "key=" + key + "; samesite=strict; secure=true;";
}
else {
    userId = localStorage.getItem("session");
    document.cookie = "key=" + userId + "; samesite=None; secure=true;";
}

function addToGallery() {
    var req = new XMLHttpRequest();
    var form = new FormData();
    file_list.forEach(element => {
        form.append("file_array[]", element);
    });

    req.onreadystatechange = () => {
        if (req.readyState == 4 && req.status == 200) {
            if (req.responseText == 1) {
                alert("Successfully Added!")
                window.location.reload()
            }
        }
    }
    req.open("POST", "save.php", true);
    req.send(form);
}