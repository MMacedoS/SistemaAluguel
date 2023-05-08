<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('#sair').click(function(){
        window.location.reload();
    });

    $('.btn-secondary').click(function(){
        window.location.reload();
    })

    $('#buttonBg').click(function(){
        event.preventDefault();
        var code = "<?=$_SESSION['code']?>";
        $.ajax({
            url:'<?=ROTA_GERAL?>/Administrativo/changeStatusBgGet/'+ code,
            method: 'GET',
            dataType: 'JSON',
            success: function(res){
                window.location.reload();
            }
        });
    });

    $(".date").datepicker( {
        dateFormat: "dd/mm/yy",
        minDate: 0 //Representa a data atual + 7
    });




</script>
<script>
    let url = "<?=ROTA_GERAL?>/";

    //   function valores(){
    //     var dias = moment($('#saida').val()).diff(moment($('#entrada').val()), 'days');
    //      var valor = $("#valor").val();
    //         $('#valores').removeClass('text-success');
    //         $('#valores').addClass('text-success');
    //         $('#valores').text("Valor Total da Estadia: R$" + valor * dias);
    //   }
      
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

    function getRequisicaoGetViaAjax(controle_metodo, tipo) {            
        $.ajax({
            url: url+controle_metodo,
            method:'GET',
            processData: false,
            dataType: 'json     ',
            success: function(data){
                if(data.status === 201){
                    preparaModalHospedadas(data.data, tipo);
                }
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/hospedadas">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/hospedadas">Atualizar?</a>'
            })
        });
        return "";
    }

    function preparaModalHospedadas(data, tipo) 
    {
        $('#label'+tipo).text(tipo);  
        
        mostrarModel('#modalHospedadas'+tipo);      

        switch (tipo) {
            case 'Consumo':
                    prepareTableConsumo(data);
                break;

                case 'Pagamento':
                    prepareTablePagamento(data);
                break;

                case 'Checkout':
                    prepareCheckout(data);
                break;
        
            default:
                    preparaModalReserva(data);
                break;
        }
        
    }

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

    function prepareTableConsumo(data)
    {
        mostrarModel('#modalHospedadas', false);

        $("#listaConsumo tr").detach();
        data.map(element => {
            var newOption = $('<tr>'+
                    '<td>'+element.descricao +' - ' + element.tipo +'</td>' +
                    '<td>'+formatDateWithHour(element.created_at)+'</td>' +
                    '<td>'+element.quantidade+'</td>' +
                    '<td>R$ '+element.valor+'</td>' +
                    '<td>R$ '+
                    parseFloat(element.valor * element.quantidade).toFixed(2)
                    +'</td>' +
                    '<td>'+
                        '<a href="#" id="'+element.id+'" class="remove-consumo" >&#10060;</a>'+
                    '</td>'+
                '</tr>');
            $("#listaConsumo").append(newOption);
        })

        $('#numeroConsumo').text(data.length);
        $('#totalConsumo').text(calculaConsumo(data).toFixed(2));
        totalConsumos = calculaConsumo(data);
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

    function prepareCheckout(data)
    {
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

    function calculaConsumo(data)
    {
        var valor = 0;
        data.forEach(element => {
            valor += element.valor * element.quantidade;
        });

        return valor;
    }

    function calculaPagamento(data)
    {
        var valor = 0;
        data.forEach(element => {
            valor += parseFloat(element.valorPagamento);
        });

        return valor;
    }

    function calculaCheckout(consumos, pagamento)
    {        
        return consumos - pagamento;
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

    function prepareTipoMaterial(value)
    {
        var res = "";
        switch (value) {
            case '1':
                res = "Diaria";
            break;
            case '2':
                res = "Mensal"
            break;
        }

        return res;
    }

    function preparaModalEditarReserva(data) 
    {
        $('#div_apartamento').removeClass('hide');
        $('#inp_entrada').val(formatDate(data[0].data_retirada));
        $('#inp_saida').val(formatDate(data[0].data_devolucao));
        $('#buscar').click();
        $('#inp_cliente').val(data[0].cliente_id);
        var newHosp= $('<option selected value="' + data[0].cliente_id + '">mesmo Cliente</option>');
        $("#inp_cliente").append(newHosp);
        $('#inp_status').val(data[0].status);
        $('#inp_multa').val(data[0].multa);
        $('#inp_telefone').val(data[0].telefone);
        $('#inp_observacao').val(data[0].obs);
        $('#inp_id').val(data[0].id);
        $('#inp_endereco').val(data[0].endereco);
        $('#inp_responsavel').val(data[0].responsavel);
        $('#btnSubmitForm').removeClass('Salvar');
        $('#btnSubmitForm').addClass('Atualizar');
        mostrarModel('#modalHospedadas', false);
        $('#exampleModalLabel').text("Atualizar Reservas");
        mostrarModel('#modal'); 
    }
    
    function preparaModalReserva(data) 
    {
        $('#id').val(data[0].id);
        $('#hospdadas_cliente').text(data[0].nome);
        $('#hospdadas_codigo').text(data[0].id);
        $('#hospdadas_multa').text(data[0].multa);
        $('#hospdadas_retirada').text(formatDate(data[0].data_retirada));
        $('#hospdadas_devolucao').text(formatDate(data[0].data_devolucao));
        $('#hospdadas_observacao').text(data[0].obs);
        $('#hospdadas_telefone').text(data[0].telefone);
        $('#hospdadas_endereco').text( data[0].endereco);
        $('#hospdadas_responsavel').text(data[0].responsavel);

        totalConsumos = data[0].consumos;
        subTotal = calculaCheckout(
            parseFloat(data[0].consumos),
            parseFloat(data[0].pag)
        );
        $('#hospdadas_consumo').text("R$ " + parseFloat(totalConsumos).toFixed(2));
        $('#hospdadas_pagamento').text("R$ " + parseFloat(data[0].pag).toFixed(2));
        mostrarModel('#modalHospedadas');
    }

    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        var entrada = $('#busca_entrada').val();
        var saida  = $('#busca_saida').val();
        var status  = $('#busca_status').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/reservas/"+texto + '_@_' + status + '_@_' + entrada + '_@_' + saida;
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Reservas");
        mostrarModel('#modal');
    });

    $(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modal').modal('hide');
        });

        $(document).on('click','.Salvar',function(){
            event.preventDefault();
            var dataEntrada = moment($('#inp_entrada').val(),"DD/MM/YYYY");
            var dataSaida = moment($('#inp_saida').val(),"DD/MM/YYYY");

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

        $(document).on('click', '.editar', function(){
            var code=$("#id").val();  
             $('#produto option').detach();
            $.ajax({
                url: url+ 'Reserva/getDadosReservas/'+ code,
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 201){
                       
                        preparaModalEditarReserva(data.data);
                    }
                }
            })    
            
        });

        $(document).on('click','.Atualizar',function(){
            event.preventDefault();
            // $('#btnSubmit').attr('disabled','disabled');
            var id = $('#id').val();
             var dataEntrada = moment($('#inp_entrada').val(),"DD/MM/YYYY");
            var dataSaida = moment($('#inp_saida').val(),"DD/MM/YYYY");

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
            var dataEntrada = moment($('#inp_entrada').val(), "DD/MM/YYYY");
            var dataSaida = moment($('#inp_saida').val(), "DD/MM/YYYY");
            
            var opcao = $('#opcao').val();            

            if(dataSaida >= dataEntrada){
                $('#div_apartamento').removeClass('hide');
                // $.ajax({
                //     url: '<=ROTA_GERAL?>/Reserva/reservaBuscaPorData/',
                //     method:'POST',
                //     data: {
                //         dataEntrada: dataEntrada._i,
                //         dataSaida: dataSaida._i
                //     },
                //     dataType: 'JSON',
                //     success: function(data){
                //         if(opcao == '')
                //             $('#apartamento option').detach();
                //         if(data.status === 200){
                //             Swal.fire({
                //                 icon: 'success',
                //                 title: 'Material Disponiveis',
                //                 text: "Possui " + data.data.length + " apartamento(s)!",
                //             });

                //             $('#div_apartamento').removeClass('hide');
                //             data.data.map(element => {
                //                 var newOption = $('<option value="' + element.id + '">' + element.numero + '</option>');
                //                 $("#apartamento").append(newOption);
                //             })
                            
                //         }
                //     }
                // })
            }            
        });   
        
        $(document).on('click', '.consumo', function(){
            var code=$("#id").val();  
             $('#produto option').detach();
            $.ajax({
                url: url+ "Material/getDadosMaterial",
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 200){
                        getRequisicaoGetViaAjax('Consumo/getDadosConsumos/'+ code, "Consumo");                       
                        data.data.map(element => {
                            var newOption = $('<option value="' + element.id + '">' + element.nome +" - " + element.tipo + '</option>');
                            $("#produto").append(newOption);
                        })
                    }
                }
            })    
            
        });
        

        $(document).on('click','.Salvar-consumo',function(){
            event.preventDefault();
            var code=$("#id").val(); 
            $.ajax({
                url: url+ 'Consumo/addConsumo/' + code,
                method:'POST',
                data: new FormData(document.getElementById("form-consumo")),
                processData: false,
                dataType: 'json',
                contentType: false,
	            cache: false,
                success: function(data){
                    if(data.status === 201){
                       $('.consumo').click();
                    }
                }
            })  
        });

        $(document).on('click', '.remove-consumo', function(){
            var code=$(this).attr("id");  
            $.ajax({
                url: url+ "Consumo/getRemoveConsumo/" + code ,
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 200){
                       $('.consumo').click();
                    }
                }
            })    
        });

        $(document).on('click', '.pagamento', function()
        {
            var code=$("#id").val();  
            getRequisicaoGetViaAjax('Pagamento/getDadosPagamentos/'+ code, "Pagamento");                       
        });

        $(document).on('click','.Salvar-pagamento',function(){
            event.preventDefault();
            var code=$("#id").val(); 
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
                        $('.pagamento').click();
                        }
                    }
                })  
            }
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
                       $('.pagamento').click();
                    }
                }
            })    
        });
    });

    $(document).on('click', '.hospedadas', function(){     
            var code=$(this).attr("id");      
            getRequisicaoGetViaAjax('Reserva/getDadosReservas/'+ code, '');                                            
        });  
        
        

    function validatePhone(phone) {
        phone = phone.value;
        var regex = new RegExp('^((1[1-9])|([2-9][0-9]))((3[0-9]{3}[0-9]{4})|(9[0-9]{3}[0-9]{5}))$'); 
        return regex.test(phone);   
    }

    function mostrarModel(model = "#modalHospedadas", situacao = true)
    {
        if(situacao){
            $(model).modal('show');    
            return;
        }

        $(model).modal('hide');    
        return;
    }
</script>
</div>
</body>
</html>