<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Material</h4>
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
                    $materiais = $this->buscaMaterial($request);
                    if(!empty($materiais)) {
                ?>
                <thead>
                    <tr>
                        <th scope="col">Material</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Status</th>
                        <th colspan="2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($materiais as $key => $value) {
                            echo '
                                <tr>
                                    <td>' . $value['nome'] . '</td>
                                    <td>' . $value['tipo'] . '</td>';
                                switch ($value['aluguel']) {                                   
                                    case '1':
                                        echo " <td>Diaria</td>";
                                    break;
                                    
                                    case '2':
                                        echo " <td>Mensal</td>";
                                    break;                                    
                                    default:
                                        # code...
                                        break;
                                }
                                    
                            echo '
                                <td><button type="button" class="btn btn-outline-primary view_data" id="'.$value['id'].'" >Editar</button> &nbsp;';                        
                                if($value['status'] == "4"){
                                    echo '<button type="button" class="btn btn-outline-primary view_Ativo" id="'.$value['id'].'" >Ativar</button> &nbsp;';
                                } 
                                if($value['status'] == '3'){
                                    echo '<button type="button" class="btn btn-outline-danger view_sujo" id="'.$value['id'].'" >Limpar</button> &nbsp;';
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
<div class="modal fade" id="modalMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <form action="" id="form" method="POST">
                <div class="modal-body" id="">                               
                    <div class="form-row">
                        <div class="col-sm-6">
                            <input type="hidden" disabled id="id" >
                            <label for="">Material</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Andaimer" required value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="descrição" required value="">
                        </div>
                    </div>                   
                    <div class="form-row">
                        <div class="col-sm-4">
                            <label for="">Tipo</label>
                            <select name="tipo" class="form-control" id="tipo">
                                <option value="Unidade">Unidade</option>
                                <option value="Metro">Metro</option>
                                <option value="Gramas">Gramas</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="">Aluguel</label>
                            <select name="aluguel" class="form-control" id="aluguel">
                                <option value="1">Diaria</option>
                                <option value="2">Mensal</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="">Valor</label>
                            <input type="number" class="form-control" step="0.01" min="0" name="valor" id="valor">
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
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/material">Atualizar?</a>'
                  }).then(()=>{
                    window.location.reload();    
                })
              }
              return Swal.fire({
                      icon: 'warning',
                      title: 'ops...',
                      text: data.message,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/material">Atualizar?</a>'
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
                    preparaModalEditarMaterial(data.data);
                }
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/material">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/material">Atualizar?</a>'
            })
        });
        return "";
    }

    function preparaModalEditarMaterial(data) {
        $('#nome').val(data[0].nome);
        $('#descricao').val(data[0].descricao);           
        $('#tipo').val(data[0].tipo);
        $('#aluguel').val(data[0].aluguel);
        $('#valor').val(data[0].valor);
        $('#id').val(data[0].id);
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar Material");
        $('#modalMaterial').modal('show');   
    }

    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/material/"+texto;
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Material");
        $('#modalMaterial').modal('show');        
    });

    $(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modalEstudantes').modal('hide');
        });

        $(document).on('click','.Salvar',function(){
            event.preventDefault();
            envioRequisicaoPostViaAjax('Material/salvarMaterial', new FormData(document.getElementById("form")));
        });

        $(document).on('click','.view_data',function(){
            var id = $(this).attr("id");
            $('#btnSubmit').removeClass('Salvar');
            envioRequisicaoGetViaAjax('Material/buscaMaterialPorId/' + id);
        });

        $(document).on('click','.Atualizar',function(){
            event.preventDefault();
            $('#btnSubmit').attr('disabled','disabled');
            var id = $('#id').val();
            envioRequisicaoPostViaAjax('Material/atualizarMaterial/' + id, new FormData(document.getElementById("form")));   
        });

        $(document).on('click','.view_Ativo',function(){    
            event.preventDefault();
            var code=$(this).attr("id");
            envioRequisicaoGetViaAjax('Material/changeStatusMaterial/'+ code);
        });    

        $(document).on('click','.view_sujo',function(){    
            event.preventDefault();
            var code=$(this).attr("id");
            envioRequisicaoGetViaAjax('Material/changeStatusMaterial/'+ code);
        });    
        
    });
</script>