<?php

class LeitorOFX
{

    private $bancoDesc;
    private $bancoCod;
    private $agencia;
    private $conta;
    private $dataInicial;
    private $dataFinal;
    private $transacoes;
    private $arquivo;

    public function __construct(string $file)
    {
        $this->transacoes = array();

        $tags = array(
            '<ORG>',
            '<BANKID>',
            '<ACCTID>',
            '<DTSTART>',
            '<DTEND>'
        );
        
        $tagsMov = array(
            array('<TRNTYPE>', 'tipo'),
            array('<DTPOSTED>', 'data'),
            array('<TRNAMT>', 'valor'),
            array('<FITID>', 'idunica'),
            array('<CHECKNUM>', 'checknum'),
            array('<MEMO>', 'desc')
        );
        $posTags = 0;
        $arquivo = fopen($file, 'r');
        
        while (!feof($arquivo)) {
            $linha = fgets($arquivo, 1024);
            $linha = utf8_encode($linha);
            $linha = trim($linha);
        
        
            if (count($tags) > $posTags) {
                if (substr($linha, 0, strlen($tags[$posTags])) == $tags[$posTags]) {
                    $tagsOk = substr($linha, strlen($tags[$posTags]), strlen($linha) - strlen($tags[$posTags]));
                    switch ($posTags) {
                        case 0:
                            $bancoDesc = $tagsOk;
                            break;
                        case 1:
                            $bancoCod = $tagsOk;
                            break;
                        case 2:
                            $conta = $tagsOk;
                            break;
                        case 3:
                            $dataFinal = $tagsOk;
                            break;
                        case 4:
                            $dataFinal = $tagsOk;
                            break;
                    }
                    $posTags++;
                }
            } else {
                if ($linha == '<STMTTRN>') {
                    $movDados = array();
                    $posmov = 0;
                }
                if ($linha == '</STMTTRN>') {
                    array_push($this->transacoes, $movDados);
                    unset($movDados);
                }
                if (isset($movDados)) {
                    if (substr($linha, 0, strlen($tagsMov[$posmov][0])) == $tagsMov[$posmov][0]) {
                        $movDados[$tagsMov[$posmov][1]] = substr($linha, strlen($tagsMov[$posmov][0]), strlen($linha) - strlen($tagsMov[$posmov][0]));
                        if ($tagsMov[$posmov][1] == 'valor') {
                            if (substr($movDados[$tagsMov[$posmov][1]], 0, 1) == '-') {
                                $movDados['es'] = "D";
                                $movDados['valor'] = $movDados['valor'] * -1;
                            } else {
                                $movDados['es'] = "C";
                            }
                        }
                        $posmov++;
                    }
                }
            }
        }
        fclose($arquivo);        
    }

    public function Banco(){
        return $this->bancoDesc;
    }

    public function BancoCod(){
        return $this->bancoCod;
    }

    public function Conta(){
        return $this->conta;
    }

    public function DataInicial(){
        return $this->dataInicial;
    }
    
    public function DataFinal(){
        return $this->dataFinal;
    }
    
    public function Transacoes(){
        return $this->transacoes;
    }    
}
