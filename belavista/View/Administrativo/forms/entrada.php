<?php
  $movimentos = $this->buscaEntrada($request);
?>
<style>
    .hide{
        visibility: hidden;
    }

    .select2 {
        width:100%!important;
    }

    .fs{
        font-size: 21px;
    }
</style>

<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Movimentações Entrada</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>
            </div>
        </div>
    </div>
<hr>
    <div class="row">   
        <div class="input-group">
            <!-- <div class="col-sm-12 mb-2">
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome, cpf" id="txt_busca" aria-label="Search" value="<=$request?>" aria-describedby="basic-addon2">
            </div> -->
            <div class="col-sm-3">
                <input type="date" name="" id="busca_entrada" class="form-control" value="<?=@$entrada ? $entrada : Date('Y-m-d') ?>">
            </div>
            <div class="col-sm-3">
                <input type="date" name="" id="busca_saida" class="form-control" value="<?=@$saida ? $saida : Date('Y-m-d')?>">
            </div>
            <!-- <div class="col-sm-3">
                <select name="" id="" class="form-control">
                    <option value="">Selecione uma empresa</option>
                    <option value="">Confirmada</option>
                    <option value="">Hospedadas</option>
                </select>
            </div>     -->
            <div class="col-sm-3">
                <select name="" id="busca_status" class="form-control">
                    <option value="">Selecione o Tipo</option>
                    <option <?=$status == 1 ? 'selected': '';?> value="1">Dinhero</option>
                    <option <?=$status == 2 ? 'selected': '';?> value="2">Cartão de Crédito</option>
                    <option <?=$status == 3 ? 'selected': '';?> value="3">Cartão de Débito</option>
                    <option <?=$status == 4 ? 'selected': '';?> value="4">Deposito/PIX</option>
                </select>
            </div>  
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="btn_busca">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>
<hr>
   
    <div class="row">
            <table class="table table-sm mr-4" id="lista">     
                <?php                    
                    if(!empty($movimentos)) {
                ?>
                <thead>
                    <tr>
                        <th scope="col">COD</th>
                        <th>Descrição</th>
                        <th scope="col">Tipo de Pagamento</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($movimentos as $key => $value) {
                            $data_saida = explode(' ', $value['created_at']);
                            echo '
                                <tr>
                                    <td>' . $value['id'] . '</td>
                                    <td>' . $value['descricao'] . '</td>
                                    <td>' . self::prepareTipo($value['tipoPagamento']) . '</td>
                                    <td>' . self::prepareTipoVenda($value['venda_id']) . '</td>
                                    <td>R$ ' . $value['valor'] . '</td>
                                </tr>
                            ';
                        }
                    ?>
                </tbody>
                <?php } else{
                    'echo <td>não possui reservas cadastradas</td>';
                }
                ?>
            </table>
        <ul class="pagination">
            <!-- Declare the item in the group -->
            <li class="page-item">
                <!-- Declare the link of the item -->
                <a class="page-link" href="<?=ROTA_GERAL?>/Administrativo/entrada/page=<?= $chave == 0 ? 0 : $chave + (-12)?>">Anterior</a>
            </li>
            <!-- Rest of the pagination items -->
          
            <li class="page-item">
                <a class="page-link" href="<?=ROTA_GERAL?>/Administrativo/entrada/page=<?=$chave + 12?>">proxima</a>
            </li>
        </ul>
    </div> 
</div>

<!-- editar -->
<div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelEntrada">Lança Entrada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">  
                <form action="" id="form" method="post">                   
                    <div class="form-row">
                        <div class="col-sm-8">
                            <label for="">Descrição</label>
                            <input type="text" name="descricao" class="form-control" id="descricao">
                        </div>
                        <div class="col-sm-4">
                            <label for="">Valor</label>
                            <input type="number" step="0.01" min="0"  value="0.00" class="form-control" name="valor" id="valor">
                        </div>

                        <div class="col-sm-6">
                            <label for="">Selecione uma forma</label>
                            <select name="pagamento" id="pagamento" class="form-control">
                                <option  value="1">Dinhero</option>
                                <option  value="2">Cartão de Crédito</option>
                                <option  value="3">Cartão de Débito</option>
                                <option  value="4">Deposito/PIX</option>
                            </select>
                        </div>  
                        <div class="col-sm-12 text-right">
                            <label for="">&nbsp;</label>
                            <button type="submit" name="salvar" id="btnSubmit" class="btn btn-primary Salvar mt-4"> &#10010; Adicionar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>

<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
<script>
    let url = "<?=ROTA_GERAL?>/";
            
      function envioRequisicaoPostViaAjax(controle_metodo, dados) {
          $.ajax({
              url: url+controle_metodo,
              method:'POST',
              data: dados,
              dataType: 'JSON',
              contentType: false,
	          cache: false,
	          processData:false,
              success: function(data){
                  if(data.status === 422){
                      $('#mensagem').removeClass('text-danger');
                      $('#mensagem').addClass('text-success');
                      $('#mensagem').text(data.message);
                  }
              }
          })
          .done(function(data) {
              if(data.status === 201){
                  return Swal.fire({
                      icon: 'success',
                      title: 'OhoWW...',
                      text: data.message,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/reservas">Atualizar?</a>'
                  }).then(()=>{
                    window.location.reload();    
                })
              }
              return Swal.fire({
                      icon: 'warning',
                      title: 'ops...',
                      text: data.message,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/reservas">Atualizar?</a>'
                  })
          });
      }

    function envioRequisicaoGetViaAjax(controle_metodo) {            
        $.ajax({
            url: url+controle_metodo,
            method:'GET',
            processData: false,
            dataType: 'json     ',
            success: function(data){
                if(data.status === 201){
                    preparaModalEditarReserva(data.data);
                }
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/reservas">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/reservas">Atualizar?</a>'
            })
        });
        return "";
    }

    function preparaModalEditarReserva(data) 
    {
        
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar Reservas");
        $('#modal').modal('show');   

        return ;
    }
    
    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Entrada");
        $('#modalEntrada').modal('show');        
    });

    $(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modal').modal('hide');
        });

        $(document).on('click','.Salvar',function(){
            event.preventDefault();            
            return envioRequisicaoPostViaAjax('Financeiro/salvarEntradas', new FormData(document.getElementById("form")));           
        });        
          
    });
</script>