function XHR()
{
    var xh;
    try {
        xh = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xh = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xh = false;
        }
    }
    if (!xh && typeof XMLHttpRequest!='undefined') {
        xh = new XMLHttpRequest();
    }
    return xh;
}
function apiData(options)
{
   /* if (typeof options.data !== 'object') return;*/
    let url = options.url || 'http://' + location.hostname + '/ajax/',
        type = options.type || 'POST',
        cType =	options.cType || 'application/x-www-form-urlencoded',
        xhr = XHR();
    xhr.open(type, url, true);
    xhr.setRequestHeader('Content-Type', cType);
    xhr.send(options.data);
    return xhr;
}
function removeArticleAlert()
{
    let id = document.getElementById('removeArticle').getAttribute('article-id'),
        dat = {
            data: 'id='+ id,
            url: 'http://127.0.0.1:8000/article/remove/'
        };
    $('#exampleModal').modal('hide');
    var xhr = apiData(dat);
    xhr.onreadystatechange = function(){
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var res = JSON.parse(xhr.responseText);
            if(res.data == 'error'){

                alert('Не удалось удалить статью!');
                return false;
            }
            window.location = 'http://' + location.hostname + ':8000';
        }};
}

function locationMap(y,x) {
    var mymap = L.map('mapid').setView([Number(y)+Number(0.5), x-1.3], 8);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 10,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoibGVvbnd3IiwiYSI6ImNrNzB0dWJnZTAwNG0zb213NWVoeWpkYjAifQ.BENQ70YnjysrOxmAHETM7w'
    }).addTo(mymap);

    var marker = L.marker([y, x]).addTo(mymap);

}

