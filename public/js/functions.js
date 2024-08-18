async function requireAjax(elemento, callback) {
    let url = elemento.dataset.urlAction;
    let method = elemento.dataset.method;
    let post_data = Array();

    if (elemento.id = 'idMesFiltro') {
        url = 
        post_data = await executarMesFiltro(elemento);
    }

    if (method == 'POST') {
        post_data = [];
    } else if (method == 'GET') {
        url = url.concat(await incrementUrl(elemento));
    }

    let req = new XMLHttpRequest();

    req.open(method, url, true);
    req.send(post_data);
    req.onload = function () {
        let resposta = responseTreatment(this);
        callback(resposta);
    }
}

function responseTreatment(response) {

    let resposta = '';
    let str_resposta = '';

    if (response.status === 200) {
        resposta = response.responseText;

        try {
            if (typeof(JSON.parse(resposta)) == 'object') {
                resposta = JSON.parse(resposta);
            }
        } catch (error) {
            str_resposta = resposta;
        }
        
    } else {
        resposta = 'Forbidden || Not Found';
    }

    if (typeof(resposta) == 'object' && str_resposta == '') {
        return JSON.stringify(resposta, null, '\t');
    } else {
        return str_resposta;
    }  
}

function createPostData() {
    let post_data = new FormData();
 
    return post_data;
}

function createPostDataMesFiltro() {
    let post_data = new FormData();
    post_data.append("mesFiltro", select_mes_filtro.value)

    return post_data;
}

function incrementUrl(elemento) {
    if (elemento.dataset.action == undefined && elemento.dataset.id == undefined && elemento.dataset.fieldId == undefined && elemento.dataset.table == undefined) {
        return '';
    }

    let table = elemento.dataset.table ?? '';
    let action = elemento.dataset.action ?? '';
    let id = elemento.dataset.id ?? '';
    let field_id = elemento.dataset.fieldId ?? '';

    let url_complement = `?action=${action}&table=${table}`.concat(`&fieldId=${field_id}&id=${id}`);

    return url_complement;
}

function dispararAlert(conteudo) {
    alert(conteudo);
}

function inserirHtml(conteudo) {
    document.body.innerHTML = conteudo;

    getScript('js/geral.js');
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

function criarLabel(str) {
    var frag = document.createDocumentFragment();
    var elem = document.createElement('label');
    elem.innerHTML = str;

    while (elem.childNodes[0]) {
        frag.appendChild(elem.childNodes[0]);
    }

    return frag;
}

function executarMesFiltro(elemento) {
    console.log(elemento);

    const currentUrl = window.location.href;
console.log(currentUrl);

}

console.log('o')