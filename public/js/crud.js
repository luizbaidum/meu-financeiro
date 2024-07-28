let acao_deletar = document.getElementsByClassName('acao-deletar');

for (let element of acao_deletar) {
    element.addEventListener('click', deletar);
}

function deletar() {
    let url_action = 'delecoes.php';
    let campo_procurar = this.dataset.campo;
    let id_deletar = this.dataset.chave;
    let tabela = this.dataset.table;

    url_action = url_action.concat('?action=').concat(tabela).concat(`&${campo_procurar}=${id_deletar}`);

    let req = new XMLHttpRequest();

    req.open('GET', url_action, true);
    req.send();
    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (window.confirm('Exclusão realizada. Atualizar página?')) {
                window.location.reload();
            }
        } else {
            alert('A exclusão não pôde ser realizada');
        }
    }
}