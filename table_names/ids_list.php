2 - Contas Casa
3 - Contas Tios
4 - Gerais Mãe
6 - Rolês/Supérfluos Luiz
7 - Sal. Líquido Luiz
8 - Pensões Mãe
9 - Honestas Luiz
10 - Devolução de Aplicação
11 - Outras Receitas
12 - Aplicação
13 - Cartões
14 - Animais
15 - Rendimento aplic.

/**
             * Ideia: se existir movimentacao em rendimentos, atualizar valor da aplicacao correspondente via trigger.
             * Se rendimento, movimentacao tbm em movimentos.
             * Rendimento -> d'onde registram-se movimentacoes de aplicacao.
             * Movimentos -> d'donte registra-se tudo, para consultas diversas.
             */
            /**
             * trigger funcionando. fazer update valores e ver se funciona com valor negativo. fazer backup do banco antes.
             */

             /**
              * atualiza_saldo rendimentos before insert.

              BEGIN
SELECT contas_investimentos.saldoAtual INTO @atual FROM contas_investimentos WHERE contas_investimentos.idContaInvest = new.idContaInvest;

UPDATE contas_investimentos SET saldoAtual = (@atual + new.valorRendimento) WHERE contas_investimentos.idContaInvest = new.idContaInvest;
END
              */
              /**
              meu bd grátis atual não suporta trigger */