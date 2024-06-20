var elemento_select = "";
var url_action = document.forms[0].baseURI;

if (document.getElementById("idMesFiltro")) {

    elemento_select = document.getElementById("idMesFiltro");

    elemento_select.addEventListener("change", () => {
        let post_data = new FormData();
        post_data.append("mesFiltro", elemento_select.value)
    
        var req = new XMLHttpRequest();
    
        req.open("POST", url_action, true);
        req.send(post_data);
        req.onreadystatechange = function () {
            if (req.readyState != 4 || (req.status != 200 && req.status != 304)) 
                return;
    
            document.body.innerHTML = req.responseText;
            getScript('js/geral.js');
        }
    });
}

function getScript(source) {
    var script = document.createElement('script');
    var prior = document.getElementsByTagName('script')[0];
    script.async = 1;

    script.onload = script.onreadystatechange = function(_, isAbort) {
        if (isAbort || !script.readyState || /loaded|complete/.test(script.readyState)) {
            script.onload = script.onreadystatechange = null;
            script = undefined;
        }
    };

    script.src = source;
    prior.parentNode.insertBefore(script, prior);
}