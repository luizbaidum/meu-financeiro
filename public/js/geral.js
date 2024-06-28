var select_mes_filtro = '';
var url_action = document.forms[0].baseURI;
var select_categoria = '';
const APLICACAO = 12;
const RESGATE = 10;

if (document.getElementById("idMesFiltro")) {

    select_mes_filtro = document.getElementById("idMesFiltro");

    select_mes_filtro.addEventListener("change", () => {
        let post_data = new FormData();
        post_data.append("mesFiltro", select_mes_filtro.value)
    
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

if (document.getElementsByClassName('validar-obrigatorios')) {
    select_categoria = document.getElementsByClassName('validar-obrigatorios')[0];

    select_categoria.addEventListener('change', function(event) {
        let value = event.target.value;
        let id_categoria = Number(value.split('-')[0]);
        let categorias_required = Array(APLICACAO, RESGATE);
        let element = '';
    
        if (categorias_required.includes(id_categoria)) {
            element = document.getElementById('idContaInvest');
            element.setAttribute('required','');
        } else {
            element = document.getElementById('idContaInvest');
            element.removeAttribute('required');
        }

        if (id_categoria == RESGATE) {
            //fazer buscar e montar campo select com objetivos da conta invest escolhida
        }
    })
}

//trcar o 10 p/ a? ver a implicancia disso.
