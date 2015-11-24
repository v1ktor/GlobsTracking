function getXMLHttpRequestObject()
{
    var ajax = null;
    if (window.XMLHttpRequest) {
        ajax = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        ajax = new ActiveXObject("MSXML2.XMLHTTP.3.0");
    }

    return ajax;
}

function run()
{
    var progressBar = document.getElementById("progressBar");
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
            progressBar.value = 100 * evt.loaded / evt.total;
        }
    })

    xhr.addEventListener("loadstart",function(evt) {
        document.getElementById("progressBar").removeAttribute("value");
    })

    xhr.onloadend = function(evt) {
        progressBar.value = evt.loaded;
    }

    var url = "/test.php";
    xhr.open("GET", url, true);
    xhr.send(null);
}