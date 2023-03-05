var gallery = document.querySelector('#gallery');
var getVal = function (elem, style) { return parseInt(window.getComputedStyle(elem).getPropertyValue(style)); };
var getHeight = function (item) { return item.querySelector('.content').getBoundingClientRect().height; };
var resizeAll = function () {
    var altura = getVal(gallery, 'grid-auto-rows');
    var gap = getVal(gallery, 'grid-row-gap');
    gallery.querySelectorAll('.gallery-item').forEach(function (item) {
        var el = item;
        el.style.gridRowEnd = "span " + Math.ceil((getHeight(item) + gap) / (altura + gap));
    });
};

window.addEventListener('resize', resizeAll);

if (!localStorage.getItem("session")) {
    var key = CryptoJS.MD5(document.cookie).toString();
    localStorage.setItem("session", key);
    document.cookie = "key=" + key + "; samesite=strict; secure=true;";
}
else {
    userId = localStorage.getItem("session");
    document.cookie = "key=" + userId + "; samesite=None; secure=true;";
}

var req = new XMLHttpRequest();
req.onreadystatechange = () => {
    if (req.readyState == 4) {
        var res = req.responseText;
        // console.log(res);
        if (res === 0) {
            alert("No images");
        }
        else {
            var images = JSON.parse(res);
            var GalleryInnerHTML = images.map((img) => `
            <div class="gallery-item">
                <div class="content">
                    <img src="${img.src}" alt="PictureBin" />
                    <div class="cover" onclick="window.location.replace('http://localhost/CTU/single.html?img=${img.name}')"></div>
                </div>
            </div>
            `
            ).join("");
            // console.log(GalleryInnerHTML);
            gallery.innerHTML = GalleryInnerHTML;
            gallery.querySelectorAll('img').forEach(function (item) {
                item.classList.add('byebye');
                if (item.complete) {
                    // console.log(item.src);
                }
                else {
                    item.addEventListener('load', function () {
                        var altura = getVal(gallery, 'grid-auto-rows');
                        var gap = getVal(gallery, 'grid-row-gap');
                        var gitem = item.parentElement.parentElement;
                        gitem.style.gridRowEnd = "span " + Math.ceil((getHeight(gitem) + gap) / (altura + gap));
                        item.classList.remove('byebye');
                    });
                }
            });
            gallery.querySelectorAll('.gallery-item').forEach(function (item) {
                item.addEventListener('click', function () {
                    // item.classList.toggle('full');
                });
            });
        }
    }
}
req.open("GET", "gallery.php", true);
req.send();


