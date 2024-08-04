async function requireAjax(elemento, callback) {
    let url = elemento.dataset.urlAction;
    let method = elemento.dataset.method;
    let post_data = Array();

    if (method == 'POST') {
        post_data = await createPostData();
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

function responseTreatment(resposta) {

  let str_resposta = '';

  if (resposta.status === 200) {
    str_resposta = resposta.responseText;
  } else {
    str_resposta = 'Forbidden || Not Found';
  }

  if (typeof(str_resposta) == 'string') {
    return str_resposta
  } else {
    return JSON.stringify(str_resposta, null, 4);
  }  
}

function createPostData() {
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