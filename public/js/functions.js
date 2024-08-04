async function requireAjax(elemento) {
    let url = '';//elemento.dataset.urlAction;
    let method = 'GET';
    let post_data = Array();

    if (method == 'POST') {
        post_data = await createPostData();
    } else if (method == 'GET') {
        url = await incrementUrl(elemento);
    }

    let req = new XMLHttpRequest();

    req.open(method, url, true);
    req.send(post_data);
    req.onload = async function () {
        let teste = await responseTreatment(this);
        console.log(teste);
    }
}

function responseTreatment(resposta) {

  let str_resposta = '';

  if (resposta.status === 200) {
    str_resposta =  resposta.response;
  } else {
    str_resposta = 'Forbidden || Not Found';
  }

  return str_resposta;
}

/*if (document.readyState === "complete" || document.readyState === "interactive") {
        
} else {
    requireAjax();
}*/

function createPostData() {
    let post_data = new FormData();
    post_data.append("mesFiltro", select_mes_filtro.value)

    return post_data;
}

function incrementUrl(elemento) {
    let url = '';
    let table = '';
    let action = '';
    let id = '';
    let field_id = '';

    return 'https://viacep.com.br/ws/01001000/json/';
}