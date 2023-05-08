<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Cliente</h4>
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
                    $hospedes = $this->buscaCliente($request);
                    if(!empty($hospedes)) {
                ?>
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">tefelone</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Status</th>
                        <th colspan="2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($hospedes as $key => $value) {
                            echo '
                                <tr>
                                    <td>' . $value['nome'] . '</td>
                                    <td>' . $value['email'] . '</td>
                                    <td>' . $value['telefone'] . '</td>
                                    <td>' . $value['tipo'] . '</td>';
                                switch ($value['status']) {
                                    case '0':
                                            echo " <td>Inativo</td>";
                                        break;
                                    case '1':
                                        echo " <td>Disponivel</td>";
                                    break;
                                    
                                    default:
                                        # code...
                                        break;
                                }
                                    
                            echo '
                                <td><button type="button" class="btn btn-outline-primary view_data" id="'.$value['id'].'" >Editar</button> &nbsp;';                        
                                if($value['status'] == "0"){
                                    echo '<button type="button" class="btn btn-outline-primary view_Ativo" id="'.$value['id'].'" >Ativar</button> &nbsp;';
                                } 
                                if($value['status'] == '1'){
                                    echo '<button type="button" class="btn btn-outline-danger view_Ativo" id="'.$value['id'].'" >Inativar</button> &nbsp;';
                                }
                                    '</td>
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
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Hospedes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <form action="" id="form" method="POST">
                <div class="modal-body" id="">                               
                    <div class="form-row">
                        <div class="col-sm-6">
                            <input type="hidden" disabled id="id" >
                            <label for="">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="nome do funcionario" required value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email" required value="">
                        </div>
                    </div>                   
                    <div class="form-row">
                        <div class="col-sm-6">
                            <label for="">CPF</label>
                            <input type="text" name="cpf" id="cpf"  placeholder="ex: 05555544455" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Telefone</label>
                            <input type="text" name="telefone" id="telefone"  placeholder="ex: 75989745632" class="form-control">
                        </div>                       
                        <div class="col-sm-12">
                            <label for="">Endereço</label>
                            <input type="text" name="endereco" id="endereco"  placeholder="ex: Rua 1, n 20, cidade - BA" class="form-control">
                        </div>   
                    </div>    
                    
                    <div class="form-row">
                        <div class="col-sm-4">
                            <label for="">Tipo</label>
                            <select name="tipo" class="form-control" id="tipo">
                                <option value="1">Normal</option>
                                <option value="2">Representante</option>
                            </select>
                        </div>

                        <!-- <div class="col-sm-5">
                            <label for="">Empresa</label>
                            <select name="empresa" class="form-control" id="empresa">
                                <option value="">Selecione ...</option>
                                <option value="1">MMS</option>
                            </select>
                        </div> -->
                        <!-- <div class="col-sm-1 mt-1">
                            <label for="">&nbsp;</label>
                            <button class="btn btn-primary mt-4">+</button>

                        </div> -->

                        <div class="col-sm-4">
                            <label for="">Status</label>
                            <select name="status" class="form-control" id="status">
                                <option value="1">Disponível</option>
                                <option value="1">Inativo</option>
                            </select>
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
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/cliente">Atualizar?</a>'
                  }).then(()=>{
                    window.location.reload();    
                })
              }
              return Swal.fire({
                      icon: 'warning',
                      title: 'ops...',
                      text: data.message,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/cliente">Atualizar?</a>'
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
                    preparaModalEditarCliente(data.data);
                }
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/cliente">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/cliente">Atualizar?</a>'
            })
        });
        return "";
    }

    function preparaModalEditarCliente(data) {
        $('#nome').val(data[0].nome);
        $('#email').val(data[0].email);         
        $('#cpf').val(data[0].cpf);         
        $('#telefone').val(data[0].telefone);          
        $('#endereco').val(data[0].endereco);
        $('#tipo').val(data[0].tipo);
        $('#empresa').val(data[0].empresa);
        $('#status').val(data[0].status);
        $('#id').val(data[0].id);
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar Cliente");
        $('#modal').modal('show');   
    }

    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/cliente/"+texto;
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Cliente");
        $('#modal').modal('show');        
    });

    $(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modal').modal('hide');
        });

        $(document).on('click','.Salvar',function(){
            event.preventDefault();
            envioRequisicaoPostViaAjax('Cliente/salvarCliente', new FormData(document.getElementById("form")));
        });

        $(document).on('click','.view_data',function(){
            var id = $(this).attr("id");
            $('#btnSubmit').removeClass('Salvar');
            envioRequisicaoGetViaAjax('Cliente/buscaClientePorId/' + id);
        });

        $(document).on('click','.Atualizar',function(){
            event.preventDefault();
            $('#btnSubmit').attr('disabled','disabled');
            var id = $('#id').val();
            envioRequisicaoPostViaAjax('Cliente/atualizarCliente/' + id, new FormData(document.getElementById("form")));   
        });

        $(document).on('click','.view_Ativo',function(){    
            event.preventDefault();
            var code=$(this).attr("id");
            envioRequisicaoGetViaAjax('Cliente/changeStatusCliente/'+ code);
        });    
        
    });
</script>