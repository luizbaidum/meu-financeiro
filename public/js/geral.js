document.getElementById("idMes").addEventListener("change", () => {
    let post_data = new FormData();
    post_data.append("mes", document.getElementById("idMes").value)

    var req = new XMLHttpRequest();

    req.open("POST", "index.php", true);
    req.send(post_data);
    req.onreadystatechange = function () {
        if (req.readyState != 4) 
            return;
        if (req.status != 200 && req.status != 304)
            return;

        document.body.innerHTML = req.responseText;
        getScript('js/geral.js');
    }
});

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