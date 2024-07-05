var select_mes_filtro = '';
var url_action = document.forms[0].baseURI;
var select_categoria = '';
const APLICACAO = 12;
const RESGATE = 10;
const CATEGORIAS_INVESTS = Array(APLICACAO, RESGATE);

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

if (document.getElementsByClassName('validar-obrigatorios').length > 0) {
    select_categoria = document.getElementsByClassName('validar-obrigatorios')[0];
    let div_content = '';
    let id_categoria = '';

    select_categoria.addEventListener('change', function(event) {
        let this_value = event.target.value;
        id_categoria = Number(this_value.split('-')[0]);
        let element = '';
    
        if (CATEGORIAS_INVESTS.includes(id_categoria)) {
            element = document.getElementById('idContaInvest');
            element.setAttribute('required','');
        } else {
            element = document.getElementById('idContaInvest');
            element.removeAttribute('required');

            if (id_categoria != RESGATE && document.getElementById('idObjetivo')) {
                //funciona mas talvez seja melhor só fazer esconder
                //validar se obj pertence a conta invest
                document.getElementById('content-obj').innerHTML = '';
                return;
            }
        }
    })

    if (document.getElementById('idContaInvest')) {
        let select_conta_invest = document.getElementById('idContaInvest');

        select_conta_invest.addEventListener('change', function() {
            let id_conta_invest = document.getElementById('idContaInvest').value;

            if (id_categoria == '') {
                alert('Por favor, selecione uma Categoria.');
            } else if (id_categoria == RESGATE) {
                div_content = document.getElementById('content-obj');

                if (document.getElementById('idObjetivo')) {
                    removeOptions(document.getElementById('idObjetivo'));
                    insertOptions(document.getElementById('idObjetivo'), options_obj, id_conta_invest);

                    return;
                }

                let new_select = document.createElement('select');
                let new_label = createElement('Objetivo');

                new_select.classList.add('form-select');
                new_select.id = 'idObjetivo';
                new_select.name = 'idObjetivo';
    
                if (id_conta_invest == '' || id_conta_invest == null || id_conta_invest == undefined) {
                    alert('Por favor, selecione uma Conta Invest.');
                }

                insertOptions(document.getElementById('idObjetivo'), options_obj, id_conta_invest);
    
                div_content.append(new_label);
                div_content.append(new_select);
            }
        })
    }
}

function createElement(str) {
    var frag = document.createDocumentFragment();
    var elem = document.createElement('label');
    elem.innerHTML = str;

    while (elem.childNodes[0]) {
        frag.appendChild(elem.childNodes[0]);
    }

    return frag;
}

function insertOptions(select, options, comparator) {
    options.forEach(function(item, value) {
        if (item.idContaInvest == comparator) {
            let value = item.idObj;
            let text = item.nomeObj;
            let opt = document.createElement('option');

            opt.value = value;
            opt.innerHTML = text;
            select.appendChild(opt);
        }
    })
}

function removeOptions(select) {
    for (let i = 0; i <= select.options.length; i++) {
        select.remove(select.options[i]);
    }
}

//trcar o 10 p/ a? ver a implicancia disso.
