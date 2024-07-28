var select_mes_filtro = '';
var url_action = document.forms[0].baseURI;
var select_categoria = '';
var APLICACAO = 12;
var RESGATE = 10;
var CATEGORIAS_INVESTS = Array(APLICACAO, RESGATE);

function executarAjax() {

}

let consulta_obj = document.getElementsByClassName('consultar-objetivos');

for (let element of consulta_obj) {
    element.addEventListener('click', consultarObjetivo);
}

function consultarObjetivo() {
    let url_action = 'cad_objetivos.php';
    let id_conta = this.dataset.chave;

    url_action = url_action.concat('?idContaConsultar=').concat(id_conta);

    let req = new XMLHttpRequest();
    req.open('GET', url_action, true);
    req.send();
    req.onreadystatechange = function () {

        console.log('ooo')
        if (this.readyState == 4 && this.status == 200) {
            if (window.confirm('Exclusão realizada. Atualizar página?')) {
                
            }
        } else {
            alert('Os objetivos não puderam ser consultados.');
        }
    }
}

if (document.getElementById("idMesFiltro")) {
    select_mes_filtro = document.getElementById("idMesFiltro");

    select_mes_filtro.addEventListener("change", () => {
        let post_data = new FormData();
        post_data.append("mesFiltro", select_mes_filtro.value)
    
        var req = new XMLHttpRequest();
    
        req.open("POST", url_action, true);
        req.send(post_data);
        req.onreadystatechange = function () {
            if (req.readyState != 4 || (req.status != 200 && req.status != 304)) {
                return;
            }

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

if (document.getElementsByClassName('select-categoria').length > 0) {
    select_categoria = document.getElementsByClassName('select-categoria')[0];
    let div_content = document.getElementById('content-obj');
    let id_categoria = '';

    select_categoria.addEventListener('change', function(event) {
        let this_value = event.target.value;
        id_categoria = Number(this_value.split('-')[0]);
        let select_conta_invest = '';

        if (id_categoria == RESGATE && !document.getElementById('idObjetivo')) {
            let new_select = document.createElement('select');
            let new_label = criarLabel('Objetivo');

            new_select.classList.add('form-select');
            new_select.id = 'idObjetivo';
            new_select.name = 'idObjetivo';
            new_select.setAttribute('required','');

            div_content.append(new_label);
            div_content.append(new_select);

            if (document.getElementById('idContaInvest').value != '') {
                let id_conta_invest = document.getElementById('idContaInvest').value;
                select_conta_invest = document.getElementById('idContaInvest');

                insertOptions(document.getElementById('idObjetivo'), options_obj, id_conta_invest);
            }
        } else {
            document.getElementById('content-obj').innerHTML = '';
        }
    
        if (CATEGORIAS_INVESTS.includes(id_categoria)) {
            select_conta_invest = document.getElementById('idContaInvest');
            select_conta_invest.setAttribute('required','');
        } else {
            select_conta_invest = document.getElementById('idContaInvest');
            select_conta_invest.removeAttribute('required');
        }
    })
}

if (document.getElementById('idContaInvest')) {
    let select_conta_invest = document.getElementById('idContaInvest');

    select_conta_invest.addEventListener('change', function() {
        let id_conta_invest = document.getElementById('idContaInvest').value;
        let id_categoria = document.getElementsByClassName('select-categoria')[0].value;
        id_categoria = Number(id_categoria.split('-')[0]);
    
        if (document.getElementById('idObjetivo')) {
            removeOptions(document.getElementById('idObjetivo'));
            insertOptions(document.getElementById('idObjetivo'), options_obj, id_conta_invest);

            return;
        }
    })
}

function criarLabel(str) {
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