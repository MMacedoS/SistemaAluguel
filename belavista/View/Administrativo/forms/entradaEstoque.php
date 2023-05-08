<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Entrada de Produtos</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>
            </div>
        </div>
    </div>

    <div class="row">        
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="busca por ..." id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="btn_busca">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive ml-3">
            <table class="table table-sm mr-4 mt-3" id="lista">     
                <?php
                    $produtos = $this->buscaEntradaEstoques($request);
                
                    if(!empty($produtos)) {
                ?>
                <thead>
                    <tr>
                        <th scope="col">Produto</th>
                        <th scope="col">Fornecedor</th>
                        <th scope="col">Data</th>
                        <th scope="col">Valor</th>
                        <th colspan="2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($produtos as $key => $value) {
                            echo '
                                <tr>
                                    <td>' . $value['descricao'] . '</td>
                                    <td>' . $value['fornecedor'] . '</td>
                                    <td>' . implode('/',
                                        array_reverse(
                                            explode('-', 
                                                explode(' ', $value['created_at'])[0]
                                            )
                                        ))
                                    . '</td>';
                                    
                                    
                            echo '<td>R$ ' . $value['valorCompra'] . '</td>
                                <td><button type="button" class="btn btn-outline-danger view_data" id="'.$value['id'].'" >Excluir</button></td>
                                </tr>
                            ';
                        }
                    ?>
                </tbody>
                <?php }?>
            </table>
        </div>
    </div>

    

<!-- editar -->
<div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Entrada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <form action="" id="form" method="POST">
                <div class="modal-body" id="">                               
                    <div class="form-row">
                        <div class="col-sm-6">
                            <input type="hidden" disabled id="id" >
                            <label for="">Produto</label>
                            <select name="produto_id" class="form-control" id="produto_id">
                            <?php
                                $prod = $this->buscaProdutosSelect()['data'];
                                foreach ($prod as $key => $value) {
                                   echo '<option value="'.$value['id'].'">'.$value['descricao'].'</option>';
                                }
                            ?>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="">Valor da Compra</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="valor" name="valor" required value="0.00">
                        </div>
                    </div>                   
                    <div class="form-row">
                        <div class="col-sm-6">
                            <label for="">Quantidade</label>
                            <input type="number" min="0" class="form-control" name="quantidade" id="quantidade">
                        </div>    

                        <div class="col-sm-6">
                            <label for="">Fornecedor</label>
                            <input type="text" class="form-control" name="fornecedor" id="fornecedor">
                        </div>
                    </div>     
                    <small>
                        <div align="center" class="mt-1" id="mensagem">
                            
                        </div>
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
                    <button type="submit" name="salvar" id="btnSubmit" class="btn btn-primary Salvar">Salvar</button>
                </div>
            </form>        
        </div>
        
    </div>
</div>
<!-- editar -->

</div>

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
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/entradaEstoque">Atualizar?</a>'
                  }).then(()=>{
                    window.location.reload();    
                })
              }
              return Swal.fire({
                      icon: 'warning',
                      title: 'ops...',
                      text: data.message,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/entradaEstoque">Atualizar?</a>'
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
                    preparaModalEditarProdutos(data.data);
                }
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/entradaEstoque">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/entradaEstoque">Atualizar?</a>'
            })
        });
        return "";
    }

    function preparaModalEditarProdutos(data) {
        $('#descricao').val(data[0].descricao);           
        $('#tipo').val(data[0].tipo);
        $('#status').val(data[0].status);
        $('#valor').val(data[0].valor);
        $('#id').val(data[0].id);
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar Entrada");
        $('#modalEntrada').modal('show');   
    }

    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/produtos/"+texto;
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Entradas");
        $('#modalEntrada').modal('show');        
    });

    $(document).ready(function(){       

        $(document).on('click','.Salvar',function(){
            event.preventDefault();
            envioRequisicaoPostViaAjax('Produto/salvarEntrada', new FormData(document.getElementById("form")));
        });

        $(document).on('click','.view_data',function(){
            var id = $(this).attr("id");
            envioRequisicaoGetViaAjax('Produto/deleteEntrada/' + id);
        }); 
        
    });
</script>