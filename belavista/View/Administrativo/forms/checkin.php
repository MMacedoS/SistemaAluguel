
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
                <h4>Check-in</h4>
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
                <button class="btn btn-primary" type="button" id="btn_busca_checkin">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>
<hr>
    <div class="row">        
            <?php
                $reservas = $this->buscaCheckin($request);                    
                if(is_array($reservas)) {
                    foreach ($reservas as $key => $value) {
                        $data_entrada = self::prepareDateBr($value['data_entrada']);
                            $data_saida = self::prepareDateBr($value['data_saida']);
                        ?>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <a href="#" class="checkin" id="<?=$value['id']?>">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                <?= $data_entrada?>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value['nome']?></div>
                                                <div class="h5 mb-0 font-weight-bold text-danger-800"><?=self::prepareStatusReserva($value['status'])?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-door-closed fa-2x text-gray-300"></i>
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

</div>
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
<script>
   
    $('#btn_busca_checkin').click(function(){
        var texto = $('#txt_busca').val();
        var entrada = $('#busca_entrada').val();
        var saida  = $('#busca_saida').val();
        var status  = $('#busca_status').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/checkin/"+texto;
    });

    $(document).ready(function()
    {        
        $(document).on('click','.checkin',function(){
            event.preventDefault();
            var code=$(this).attr("id");
            Swal.fire({
                title: 'Deseja fazer o check-in esta reserva? reserva:'+ code,
                showDenyButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: `Não`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    envioRequisicaoGetViaAjax('Reserva/changeCheckinReservas/'+ code);
                } else if (result.isDenied) {
                    Swal.fire('nenhuma mudança efetuada', '', 'info')
                }
            })
        });
       
    });
</script>