var APLICACAO = 12;
var RESGATE = 10;
var CATEGORIAS_INVESTS = Array(APLICACAO, RESGATE);
var FORMULARIOS_TELA = document.querySelectorAll('.form-ajax');
var ACAO_DELETAR = document.getElementsByClassName('acao-deletar');

if (document.getElementsByClassName('consultar-objetivo')) {
    var btn_objetivos = document.getElementsByClassName('consultar-objetivo');
    
    for (let btn of btn_objetivos) {
        btn.addEventListener('click', function () {
            requireAjax(btn);
        })
    }
}

if (document.getElementById('idMesFiltro')) {
    var select_mes_filtro = document.getElementById('idMesFiltro');

    select_mes_filtro.addEventListener('change', () => {
        let formulario = document.getElementById('idFormMesFiltro');
        requireAjax(formulario);
    });
}

if (document.getElementsByClassName('select-categoria').length > 0) {
    let select_categoria = document.getElementsByClassName('select-categoria')[0];
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

FORMULARIOS_TELA.forEach(function (formulario, valor) {
    formulario.addEventListener('submit', function(event) {
        event.preventDefault();
        requireAjax(formulario);
    })
})

for (let btn of ACAO_DELETAR) {
    btn.addEventListener('click', deletar);
}