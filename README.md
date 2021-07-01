# leitorofx

Leitor simples de OFX modelo extrato bancário
Retorna os seguintes campos

- Nome do banco
- Código do banco
- Número da conta
- Data inicial
- Data final
- Transações
  - tipo
  - data
  - valor
  - Débido ou Crédito
  - idunica
  - checknum
  - desc
  
Como usar:

require('leitorofx/src/leitorofx.php');

$ofxInfo = new LeitorOFX('extrato.ofx');

$ofxInfo->Banco() //Retorna a descricao do banco

$ofxInfo->BancoCod() //Retorna o código do banco

$ofxInfo->Conta() //Retorna a conta

$ofxInfo->DataInicial() //Retorna a data inicial do extrato no formato AAAAMMDD

$ofxInfo->DataFinal() //Retorna a data final do extrato no formato AAAAMMDD
    
$ofxInfo->Transacoes() //Retorna as transações do extrato em formato de array
Para ler todos os dados das transações é precisa usar um foreach ex:

foreach($ofxInfo->Transacoes() AS $dadosTransacao){
  print_r($dadosTransacao);
}
