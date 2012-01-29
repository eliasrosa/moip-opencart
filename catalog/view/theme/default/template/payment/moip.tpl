$pesodetodososprodutos=0;

foreach ($products as $product) {
    if (!isset($product['disconto'])) {
        $preco = $product['valor'];
    } else {
        $preco = $product['disconto'];
    }
    $preco = preg_replace("/[^0-9]/", "", $preco);
    $preco = $preco*$product['quantidade'];
    $pesoprod = preg_replace("/[^0-9]/", "", $product['peso']);
    $descricaoproduto = $product['descricao'];
    $valortotalpdedido=$valortotalpdedido+$preco;
    $descicaaao.='Produto: '.$descricaoproduto.' Qtd: '.$product['quantidade'].' ';
    $pesodetodososprodutos=$pesodetodososprodutos+$pesoprod;
}

if(isset($cupondedesconto)){
    if($cupondedesconto>0){
        $cupondedesconto = preg_replace("/[^0-9]/", "", $cupondedesconto);
        $cupooooonddesconto=$cupondedesconto;
        $valortotalpdedido=$valortotalpdedido-$cupooooonddesconto;
    }
}
?>
<input type="hidden" name="id_carteira" value="<?PHP echo $mailpg; ?>" />
<input type="hidden" name="id_transacao" value="<?PHP echo $codipedido; ?>" />
<input type="hidden" name="nome" value="<?PHP echo $nometranzacao; ?>" />
<input type="hidden" name="descricao" value="<?PHP echo $descicaaao; ?>" />
<?php
/* Erro no calculo do frete */
if(!isset($errofrete)){
    $valortotalpdedido=$valortotalpdedido+$valorfe;
    ?>   
        <input type="hidden" name="valor" value="<?PHP echo $valortotalpdedido; ?>" />
        <?php   
} else {
    ?>
        <input type="hidden" name="valor" value="<?PHP echo $valortotalpdedido; ?>" />   
        <input type="hidden" name="peso_compra" value="<?PHP echo $pesodetodososprodutos; ?>" />   
        <input type="hidden" name="frete" value="1" />
        <?php   
}
?>
<input type="hidden" name="pagador_nome" value="<?PHP echo $first_name.' '.$last_name; ?>" />
<input type="hidden" name="pagador_cep" value="<?PHP echo $zip; ?>" />
<input type="hidden" name="pagador_logradouro" value="<?PHP echo $address1; ?>" />
<input type="hidden" name="pagador_numero" value="<?PHP echo $numero; ?>" />
<input type="hidden" name="pagador_complemento" value="<?PHP echo $address2; ?>" />
<input type="hidden" name="pagador_bairro" value="<?PHP echo $bairro; ?>" />
<input type="hidden" name="pagador_cidade" value="<?PHP echo $city; ?>" />
<input type="hidden" name="pagador_estado" value="<?PHP echo $estado; ?>" />
<input type="hidden" name="pagador_pais" value="<?PHP echo $country; ?>" />
<input type="hidden" name="pagador_telefone" value="<?PHP echo $ddd.$telephone; ?>" />
<input type="hidden" name="pagador_email" value="<?PHP echo $email; ?>" />
<input type="hidden" name="pagador_cpf" value="<?PHP //echo $cpfcli; ?>" />
<input type="hidden" name="pagador_celular" value="<?PHP //echo $celular; ?>" />
<input type="hidden" name="pagador_sexo" value="<?PHP //echo $sexocli; ?>" />
<input type="hidden" name="pagador_data_nascimento" value="<?PHP //echo $datanasci; ?>" />
</form>
<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
<br />
<center><img src="http://www.moip.com.br/imgs/banner_parc_6_1_3.gif" border="0" alt="moip" /><br /><br /><img src="http://www.moip.com.br/imgs/flags.JPG" border="0" /><br /><br />
Após clicar no botão <b>Pagar</b> que está abaixo, você será redirecionado para o MoIP para efetuar o pagamento.
</center>  
<br />
<br />
</div>


<div class="buttons">
 <!-- <a id="button-confirm" class="button"><img src="image/formas_pg/bt_pagar_c01_e04.png" border="0" /></a> -->
 <a id="button-confirm" ><img src="https://www.moip.com.br/imgs/buttons/bt_pagar_c01_e04.png" border="0" /></a> 
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
   $.ajax({
      type: 'GET',
      url: 'index.php?route=payment/moip/confirm',
      beforeSend: function() {
         $('#button-confirm').attr('disabled', true);
         
         $('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> Finalizando Pedido.</div>');
      },
      success: function() {
         $('#payment').submit();
      }
   });
});
//--></script>
