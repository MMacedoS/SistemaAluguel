
<h4>Lista de Contratos</h4>
<br>
    <div class="input-group">
        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
        <div class="input-group-append">
            <button class="btn btn-primary" type="button" id="btn_busca">
                <i class="fas fa-search fa-sm"></i>
            </button>   
        </div>
    </div>
</br>
<div class="table-responsive-sm mt-3">
    <table class="table ml-3">
        <thead>
            <tr>
                <th scope="col">Contrato</th>
                <th scope="col">Aluno</th>
                <th scope="col">Resposáveis</th>
                <th scope="col">Data de renovação</th>
                <th scope="col">Visualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($this->buscaContrato($request) as $key => $value) {
                    echo ' <tr>
                        <th scope="row">'.$value['contrato_id'].'</th>
                        <td>'.$value['nome'].'</td>
                        <td>'.$value['nome_um']."/".$value['nome_dois'].'</td>
                        <td>'.$value['created_at'].'</td>
                        <td><a target="_blank" href="'.ROTA_GERAL.'/Contrato/contrato_assinado.php?id_contrato='.$value['contrato_id'].'"> <i class="fas fa-record-vinyl"></i></a></td>
                        </tr>';
                }
            ?>
        </tbody>
    </table>
</div>


<script>
    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/contratos/"+texto;
    });
</script>