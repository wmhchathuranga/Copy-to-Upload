if (!localStorage.getItem("session")) {
    var key = CryptoJS.MD5(document.cookie).toString();
    localStorage.setItem("session", key);
    document.cookie = "key=" + key + "; samesite=strict; secure=true;";
}
else {
    userId = localStorage.getItem("session");
    document.cookie = "key=" + userId + "; samesite=None; secure=true;";
}

var img_tag = document.getElementById('img');
var req = new XMLHttpRequest();
var form = new FormData();

const params = new URLSearchParams(window.location.search);
const img = params.get('img');

form.append('img',img);
req.onreadystatechange = ()=> {
    if(req.readyState == 4){
        const res = req.responseText;
        if(res != 0){
            let imgJosn = JSON.parse(res);
            img_tag.src = imgJosn.src;
            // alert(imgJosn.src);
        }
    }
}

req.open("POST","single.php",true);
req.send(form);