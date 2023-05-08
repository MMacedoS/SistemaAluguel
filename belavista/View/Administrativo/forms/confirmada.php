
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
                <h4>Reservas Confirmadas</h4>
            </div>
            <div class="col-sm-4 text-right">
                <a href="<?=ROTA_GERAL?>/Administrativo/consultas" class="btn btn-primary" id="novo">Voltar</a>
            </div>
        </div>
    </div>
<hr>
    <div class="row">   
        <div class="input-group">         
            
            <div class="col-sm-11 mt-2">
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome, cpf" id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
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
            <?php
                $reservas = $this->buscaConfirmada($request);                    
                if(is_array($reservas)) {
                    foreach ($reservas as $key => $value) {
                        ?>

                    <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <a href="#" class="confirmada" id="<?=$value['id']?>">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> Hospede:
                                                <?= $value['nome']?>
                                                </div>
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Entrada:
                                                <?php echo implode('/',
                                                    array_reverse(
                                                        explode('-', $value['dataEntrada'])
                                                    ));?>
                                                </div>                      
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Codigo:
                                                <?= $value['id']?>
                                                </div>                                                
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">APT <?= $value['numero']?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                                            </div>                                                         
                                        </div>
                                    </div>
                                </a>    
                            </div>
                        </div>
                        
                    
            <?php }//foreach
                }//if
            ?>       
    </div>    


<!-- editar -->
<div class="modal fade" id="modalPagamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelPagamento">Lançar Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">  
                <form action="" id="form-pagamento" method="post">
                    <div class="row">
                        <input type="hidden" name="" disabled id="idReserva">
                        <div class="table-responsive" style="height: 250px">
                            <table class="table bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            Data
                                        </th>
                                        <th>
                                            Descrição
                                        </th>
                                        <th>
                                            Tipo
                                        </th>
                                        <th>
                                            Valor
                                        </th>
                                        <th>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="listaPagamento">

                                </tbody>
                            </table>
                           
                        </div>    
                        <div class="col-sm-3 text-right text-success">
                            <small class="text-end">Registro(s) <span id="numeroPagamento">0</span></small> 
                        </div> 

                        <div class="col-sm-3 text-right text-danger">
                            <small class="text-end">Consumos(s) R$ <span id="totalConsumos"></span> </small> 
                        </div>   

                        <div class="col-sm-6 text-right">
                            <small class="text-end">Total R$ <span id="totalPagamento"></span></small> 
                        </div>      
                    </div>
                    <hr>
                    <div class="form-row">
                        <div class="col-sm-3">
                            <label for="">Tipo</label>
                            <select name="tipo" class="form-control" id="tipo">
                                <option value="2">Cartão de Crédito</option>
                                <option value="3">Cartão Débito</option>
                                <option value="4">Déposito/PIX</option>
                                <option value="1">Dinheiro</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="">Valor</label>
                            <input type="number" step="0.01" min="0.00"  value="0" class="form-control" name="valor" id="valor">
                        </div>
                        <div class="col-sm-3">
                            <label for="">Descrição</label>
                            <input type="text" value="" class="form-control" name="descricao" id="descricao">
                        </div>
                        <div class="col-sm-3 text-center">
                            <label for="">&nbsp;</label>
                            <button type="submit" name="salvar" id="btnSubmit" class="btn btn-primary Salvar-pagamento mt-4"> &#10010; Pagamento</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
<!-- editar -->


<script>
let url = "<?=ROTA_GERAL?>/";

var totalConsumos = 0;
 var subTotal = 0;

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
                
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/checkout">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/checkout">Atualizar?</a>'
            })
        });
        return "";
    }

    function getRequisicaoGetViaAjax(controle_metodo, id) {            
        $.ajax({
            url: url+controle_metodo,
            method:'GET',
            processData: false,
            dataType: 'json     ',
            success: function(data){
                if(data.status === 201){
                    preparaModalHospedadas(data.data, id);
                }
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/checkout">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/checkout">Atualizar?</a>'
            })
        });
        return "";
    }

    function calculaCheckout(consumos, pagamento)
    {        
        return consumos - pagamento;
    }

    function prepareTablePagamento(data)
    {
        $("#listaPagamento tr").detach();
        data.map(element => {
            var newOption = $('<tr>'+                    
                    '<td>'+formatDate(element.dataPagamento)+'</td>' +
                    '<td>'+element.descricao+'</td>' +
                    '<td>'+
                        prepareTipo(element.tipoPagamento)
                    +'</td>' +
                    '<td>R$ '+parseFloat(element.valorPagamento).toFixed(2)+'</td>' +
                    '<td>'+
                        '<a href="#" id="'+element.id+'" class="remove-pagamento" >&#10060;</a>'+
                    '</td>'+
                '</tr>');
            $("#listaPagamento").append(newOption);
        })

        $('#numeroPagamento').text(data.length);
        $('#totalConsumos').text(parseFloat(totalConsumos).toFixed(2));
        $('#totalPagamento').text(calculaPagamento(data).toFixed(2));
        if(subTotal > 0){
            $('#valor').val(subTotal);
        }


    }

    function calculaPagamento(data)
    {
        var valor = 0;
        data.forEach(element => {
            valor += parseFloat(element.valorPagamento);
        });

        return valor;
    }


$(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modalCheckout').modal('hide');
        });
        
        $('.js-example-basic-single').select2();    
    
        $(document).on('click', '.hospedadas', function(){     
            var code=$(this).attr("id");       
            getRequisicaoGetViaAjax('Reserva/getDadosReservas/'+ code, "Checkout");                                           
        }); 
        
        $(document).on('click', '.executar-checkout', function(){
            var code=$('#idReserva').val();
            envioRequisicaoGetViaAjax("Reserva/executaCheckout/" + code);  
        });

});

function preparaModalHospedadas(data, tipo) 
    {
        $('#labelPagamento').text("Adicionar Pagamento");
        $('#idReserva').val(tipo);
        $('#modalPagamento').modal('show');  

        prepareTablePagamento(data);
        
    }

    function prepareCheckout(data)
    {
        $('#idReserva').val(data[0].id);
        $('#nomeHospede').text(data[0].nome);
        $('#codigoReserva').text(data[0].id);
        $('#numeroMaterial').text(data[0].numero);
        $('#totalHospedagem').text("R$ " + parseFloat(data[0].consumos).toFixed(2));
        $('#totalPago').text("R$ " + data[0].pag);
        var total = calculaCheckout(
            parseFloat(data[0].consumos),
            parseFloat(data[0].pag)
        ).toFixed(2);
        
        if(total > 0) {
            $('#restante').addClass('text-danger');
            $('#restante').text("Resta pagar R$ " + total);
            return ;
        }
        $('#restante').addClass('text-success');
        $('#restante').text("Crédito disponivel R$ " + total * (-1));

        if(total == 0)
        {
            $('#restante').text("Fechamento disponivel");
            $('#btnSubmit').attr('disabled',false);
        }
    }
    $(document).ready(function(){
        $(document).on('click','.Salvar-pagamento',function(){
                event.preventDefault();
                var code = $("#idReserva").val(); 
                if ($('#valor').val() > 0) {
                    $.ajax({
                        url: url+ 'Pagamento/addPagamento/' + code,
                        method:'POST',
                        data: new FormData(document.getElementById("form-pagamento")),
                        processData: false,
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        success: function(data){
                            if(data.status === 201){
                            $('.confirmada').click();
                            }
                        }
                    })  
                }
            });

            $(document).on('click', '.confirmada', function()
            {
                var code=$(this).attr("id");    
                getRequisicaoGetViaAjax('Pagamento/getDadosPagamentos/'+ code, code);                       
            });

            $(document).on('click', '.remove-pagamento', function(){
            var code=$(this).attr("id");  
            $.ajax({
                url: url+ "Pagamento/getRemovePagamento/" + code ,
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 200){
                       $('.confirmada').click();
                    }
                }
            })    
        });
    });


    function formatDate(value)
    {
        const date = value.split('-');
        return ''+date[2]+ '/' + date[1] + '/' + date[0];
    }

    function formatDateWithHour(value)
    {
        const date = value.split(' ');
        return formatDate(date[0]) + ' ' + date[1];
    }

    function prepareTipo(value)
    {
        var res = "";
        switch (value) {
            case '1':
                res = "Dinheiro";
            break;
            case '2':
                res = "Cartão de Crédito"
            break;
            case '3':
               res =  "Cartão de Débito"
            break;
            case '4':
               res =  "Déposito/PIX"
            break;
        }

        return res;
    }

</script>