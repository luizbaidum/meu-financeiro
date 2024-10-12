let acao_deletar = document.getElementsByClassName('acao-deletar');

for (let btn of acao_deletar) {
    btn.addEventListener('click', deletar);
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
                if (window.confirm('Exclusão realizada. Atualizar página?')) {
                    window.location.reload();
                }
            } else {
                alert('A exclusão não pôde ser realizada');
            }
        }
    }
}

function salvarEdicao(element) {
    let url_action = '../../sql/updates.php';
    let id_atualizar = element.id;
    let tabela = element.dataset.table;
    let linha = element.closest('tr');
    let elementos_atualizar = linha.getElementsByClassName('set-edit-movimento');

    let form_data = new FormData();

    form_data.append('action', tabela);
    form_data.append('id', id_atualizar);

    Array.from(elementos_atualizar).forEach(el => {
        let campo_atualizar = el.name;

        form_data.append(campo_atualizar, el.value);
    });

    console.log(form_data);
    return;

    let req = new XMLHttpRequest();

    req.open('POST', url_action, true);
    req.send();
    req.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                if (window.confirm('Exclusão realizada. Atualizar página?')) {
                    window.location.reload();
                }
            } else {
                alert('A exclusão não pôde ser realizada');
            }
        }
    }
}