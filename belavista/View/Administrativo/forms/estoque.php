<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Nivel de Estoque</h4>
            </div>
            <!-- <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>
            </div> -->
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
                    $produto = $this->buscaEstoques($request);
                    
                    if(!empty($produto)) {
                ?>
                <thead>
                    <tr>
                        <th scope="col">Produto</th>
                        <th scope="col">Saldo Agora</th>
                        <th scope="col">Saldo Antes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($produto as $key => $value) {
                            echo '
                                <tr>
                                    <td>' . $value['descricao'] . '</td>
                                    <td>' . $value['saldoAtual'] . '</td>
                                    <td>' . $value['saldoAnterior'] . '</td>
                                </tr>';
                        }
                    ?>
                </tbody>
                <?php }?>
            </table>
        </div>
    </div>

    
</div>

<script>
     $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/estoque/"+texto;
    });
</script>