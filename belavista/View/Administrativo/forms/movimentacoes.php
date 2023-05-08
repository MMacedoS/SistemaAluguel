<?php
  $movimentos = $this->buscaMovimentos($request);
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
                <h4>Movimentações</h4>
            </div>
            <div class="col-sm-4 text-right">
                <!-- <button class="btn btn-primary" id="novo">Adicionar</button> -->
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
                    <option <?=$status == 1 ? 'selected': '';?> value="1">Entrada</option>
                    <option <?=$status == 2 ? 'selected': '';?> value="2">Saida</option>
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
        <div class="col-sm-4 text-info">Entrada: R$ <?=self::calculateEntrada($movimentos);?></div>
        <div class="col-sm-4 text-danger">Saida: R$ <?=self::calculateSaida($movimentos);?></div>
        <div class="col-sm-4 text-success">SubToral: R$ <?=number_format(self::calculateEntrada($movimentos) - self::calculateSaida($movimentos),2,',','')?></div>
    </div>
    <div class="row">
        <div class="table-responsive ml-3">
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
                        <th scope="col">Data</th>
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
                                    <td>' . self::prepareTipo($value['tipo']) . '</td>
                                    <td>' . self::prepareTipoEntradas($value['entrada_id']) . '</td>
                                    <td>' . implode('/', array_reverse(explode('-', $data_saida[0])))  . '</td>
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
        </div>
        <ul class="pagination">
            <!-- Declare the item in the group -->
            <li class="page-item">
                <!-- Declare the link of the item -->
                <a class="page-link" href="<?=ROTA_GERAL?>/Administrativo/movimentacoes/page=<?= $chave == 0 ? 0 : $chave + (-12)?>">Anterior</a>
            </li>
            <!-- Rest of the pagination items -->
          
            <li class="page-item">
                <a class="page-link" href="<?=ROTA_GERAL?>/Administrativo/movimentacoes/page=<?=$chave + 12?>">proxima</a>
            </li>
        </ul>
    </div>    


</div>
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
<script>
    let url = "<?=ROTA_GERAL?>/";

      function valores(){
        var dias = moment($('#saida').val()).diff(moment($('#entrada').val()), 'days');
         var valor = $("#valor").val();
            $('#valores').removeClass('text-success');
            $('#valores').addClass('text-success');
            $('#valores').text("Valor Total da Estadia: R$" + valor * dias);
      }
      
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
        $('#entrada').val(data[0].dataEntrada);
        $('#saida').val(data[0].dataSaida);
        $('#buscar').click();
        $('#hospedes').val(data[0].hospede_id);
        $('#tipo').val(data[0].tipo);
        $('#valor').val(data[0].valor);
        $('#status').val(data[0].status);
        $('#observacao').val(data[0].obs);
        $('#id').val(data[0].id);
        $("#apartamento").append('<option selected value="'+data[0].apartamento_id+'">mesmo Material</option>');
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar Reservas");
        $('#modal').modal('show');   

        return ;
    }

    $('#btn_busca').click(function(){
        var entrada = $('#busca_entrada').val();
        var saida  = $('#busca_saida').val();
        var status  = $('#busca_status').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/movimentacoes/"+ '_@_' + status + '_@_' + entrada + '_@_' + saida;
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Reservas");
        $('#modal').modal('show');        
    });

    $(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modal').modal('hide');
        });

        $(document).on('click','.Salvar',function(){
            event.preventDefault();
            var dataEntrada = moment($('#entrada').val());
            var dataSaida = moment($('#saida').val());

            if(dataSaida > dataEntrada){
                return envioRequisicaoPostViaAjax('Reserva/salvarReservas', new FormData(document.getElementById("form")));
            }
        });

        $(document).on('click','.view_data',function(){
            var id = $(this).attr("id");
            $('#btnSubmit').removeClass('Salvar');
            $('#opcao').val('atualiza')
            envioRequisicaoGetViaAjax('Reserva/buscaReservaPorId/' + id);
        });

        $(document).on('click','.Atualizar',function(){
            event.preventDefault();
            // $('#btnSubmit').attr('disabled','disabled');
            var id = $('#id').val();
            var dataEntrada = moment($('#entrada').val());
            var dataSaida = moment($('#saida').val());

            if(dataSaida > dataEntrada){
                return envioRequisicaoPostViaAjax('Reserva/atualizarReserva/' + id, new FormData(document.getElementById("form")));   
            }
            return  Swal.fire({
                        icon: 'warning',
                        title: 'Datas invalidas',
                        text: "Possui verifique as datas!",
                    });
        });

        $(document).on('click','.view_Ativo',function(){    
            event.preventDefault();
            var code=$(this).attr("id");
            Swal.fire({
                title: 'Deseja cancelar esta reserva?',
                showDenyButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: `Não`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    envioRequisicaoGetViaAjax('Reserva/changeStatusReservas/'+ code);
                } else if (result.isDenied) {
                    Swal.fire('nenhuma mudança efetuada', '', 'info')
                }
            })
        });    

        $('.js-example-basic-single').select2();    
    
        $(document).on('click', '#buscar', function(){
            var dataEntrada = moment($('#entrada').val());
            var dataSaida = moment($('#saida').val());
            
            var opcao = $('#opcao').val();            

            if(dataSaida >= dataEntrada){
                $.ajax({
                    url: '<?=ROTA_GERAL?>/Reserva/reservaBuscaPorData/',
                    method:'POST',
                    data: {
                        dataEntrada: dataEntrada._i,
                        dataSaida: dataSaida._i
                    },
                    dataType: 'JSON',
                    success: function(data){
                        if(opcao == '')
                            $('#apartamento option').detach();
                        if(data.status === 200){
                            Swal.fire({
                                icon: 'success',
                                title: 'Material Disponiveis',
                                text: "Possui " + data.data.length + " apartamento(s)!",
                            });

                            $('#div_apartamento').removeClass('hide');
                            data.data.map(element => {
                                var newOption = $('<option value="' + element.id + '">' + element.numero + '</option>');
                                $("#apartamento").append(newOption);
                            })
                            
                        }
                    }
                })
            }            
        });        
    });
</script>