async function requireAjax(elemento) {
    let url = elemento.dataset.urlAction;
    let method = elemento.dataset.method;
    let div_append = elemento.dataset.divAppend ?? null;
    let callback = elemento.dataset.callback ?? null;
    let post_data = Array();

    if (method == 'POST') {
        post_data = await createPostData(elemento);
    } else if (method == 'GET') {
        url = url.concat(await incrementUrl(elemento));
    }

    //Caso específico
    if (elemento.id == 'idFormMesFiltro') {
        let ret = await executarMesFiltro(elemento);

        method = 'POST';
        url = ret[0];
        post_data = ret[1];
    }

    let req = new XMLHttpRequest();

    req.open(method, url, true);
    req.send(post_data);
    req.onload = function () {
        let resposta = responseTreatment(this);
        if (callback != null) {
            window[callback](resposta);
        } else {
            if (div_append) {
                insertIntoDiv(div_append, resposta);
            } else {
                insertIntoBody(resposta);
            }
        }
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

function createPostData(formulario) {
    let post_data = new FormData();

    new FormData(formulario).forEach((value, key) => {
        post_data.append(key, value);
    });
 
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
    let str = '';
    let arr = Array();

    try {
            arr = JSON.parse(conteudo);
        } catch (e) {
            if (e instanceof SyntaxError) {
                console.log('Json SyntaxError');
            } else {
                console.log("Json error, but it isn't a SyntaxError.");
            }
        }

    if (arr.length > 0) {
        arr.forEach(function (i, v) {
            for (var name in i) {
                str = str + `${name}: ${i[name]} \n`;
            }

            str = str + '\n';
        })
    } else {
        str = conteudo;
    }

    alert(str);
}

function insertIntoDiv(div, conteudo) {
    document.getElementById(div).innerHTML = conteudo;
    getScript('js/geral.js');
}

function insertIntoBody(conteudo) {
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

function insertOptions(select_element, options, comparator) {
    options.forEach(function(item, value) {

        if (item.idContaInvest == comparator) {
            let value = item.idObj;
            let text = item.nomeObj;
            let opt = document.createElement('option');

            opt.value = value;
            opt.innerHTML = text;
            select_element.appendChild(opt);
        }
    })
}

function removeOptions(select_element) {
    for (let i = 0; i <= select_element.options.length; i++) {
        select_element.remove(select_element.options[i]);
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

async function executarMesFiltro(elemento) {
    let current_url = window.location.href;
    let data = createPostData(elemento);

    return Array(current_url, data);
}

function criarInputText(nome, valor) {
    return `<input type="text" class="set-edit-movimento" name="${nome}" value="${valor}"></input>`
}

function criarSelect(options_list, name) {
    return `<select class="set-edit-movimento" name="${name}">
                ${options_list}
            </select>`;
}

function deletar() {
    let url_action = '../../sql/delecoes.php';
    let campo_procurar = this.dataset.campo;
    let id_deletar = this.dataset.chave;
    let tabela = this.dataset.table;

    url_action = url_action.concat('?action=').concat(tabela).concat(`&${campo_procurar}=${id_deletar}`);

    let req = new XMLHttpRequest();
    req.open('GET', url_action, true);
    req.send();
    req.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {

                let ret_json = JSON.parse(this.response);

                if (ret_json.status) {
                    if (window.confirm('Exclusão realizada. Atualizar página?')) {
                        window.location.reload();
                    }
                } else {
                    alert('A exclusão não pôde ser realizada');
                }
            } else {
                alert('A exclusão não pôde ser realizada');
            }
        }
    }
}