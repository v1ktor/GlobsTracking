function getXMLHttpRequestObject()
{
    var ajax = null;
    if (window.XMLHttpRequest) {
        ajax = new XMLHttpRequest();
    } else {
        alert("Your browser doesn't support XMLHttpRequest object");
    }

    return ajax;
}

function run()
{
    var xhr = getXMLHttpRequestObject();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status >=200 && xhr.status < 300 || xhr.status == 304) {
                var div = document.getElementById("response");
                div.innerHTML = xhr.responseText;
            }

        }
    }

    xhr.addEventListener("progress",function(evt) {
        if (evt.lengthComputable) {
            console.log(evt.loaded + ' / ' + evt.total);
        }
    })

    xhr.addEventListener("loadstart",function(evt) {
        NProgress.start();
    })

    xhr.onloadend = function(evt) {
        NProgress.done();
    }

    var url = "/defect/getjiraglobs";
    xhr.open("GET", url, true);
    xhr.send(null);
}