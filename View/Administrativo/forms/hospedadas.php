
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

<div class="container-fluid">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Lista de reservas em atividade</h4>
               
            </div>
            <div class="col-sm-4 text-right">
                <a href="<?=ROTA_GERAL?>/Administrativo/consultas" class="btn btn-primary" id="novo">Voltar</a>
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
                <input type="date" name="" id="busca_entrada" class="form-control" value="<?=$entrada?>">
            </div>
            <div class="col-sm-3">
                <input type="date" name="" id="busca_saida" class="form-control" value="<?=$saida?>">
            </div>
            <!-- <div class="col-sm-3">
                <select name="" id="" class="form-control">
                    <option value="">Selecione uma empresa</option>
                    <option value="">Confirmada</option>
                    <option value="">Hospedadas</option>
                </select>
            </div>     --> 
            
            <div class="col-sm-5 mt-2">
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome, cpf" id="txt_busca" aria-label="Search" value="<?=$texto?>" aria-describedby="basic-addon2">
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
                    $reservas = $this->buscaReservas($request);
                    // var_dump($reservas);
                    if(!empty($reservas)) {
                ?>
                    <?php
                        foreach ($reservas as $key => $value) {
                            $data_entrada = self::prepareDateBr($value['data_entrada']);
                            $data_saida = self::prepareDateBr($value['data_saida']);
                           ?>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <a href="#" class="hospedadas" id="<?=$value['id']?>">
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

                    <?php
                        }
                    }
                    ?>
        </div>
    <div class="row">
        <ul class="pagination">
            <!-- Declare the item in the group -->
            <li class="page-item">
                <!-- Declare the link of the item -->
                <a class="page-link" href="<?=ROTA_GERAL?>/Administrativo/reservas/page=<?= $chave == 0 ? 0 : $chave + (-12)?>">Anterior</a>
            </li>
            <!-- Rest of the pagination items -->
          
            <li class="page-item">
                <a class="page-link" href="<?=ROTA_GERAL?>/Administrativo/reservas/page=<?=$chave + 12?>">proxima</a>
            </li>
        </ul>
    </div>  

<!-- editar -->
<div class="modal fade" id="modalHospedadasConsumo" tabindex="0" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lançar Material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">  
                <form action="" id="form-consumo" method="post">
                    <div class="row">
                        <div class="table-responsive" style="height: 250px">
                            <table class="table bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            Material
                                        </th>
                                        <th>
                                            Data
                                        </th>
                                        <th>
                                            Quantidade
                                        </th>
                                        <th>
                                            Valor
                                        </th>
                                        <th>
                                            Total
                                        </th>
                                        <th>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="listaConsumo">

                                </tbody>
                            </table>
                           
                        </div>       
                        <div class="col-sm-6 text-right">
                            <small class="text-end">Registro(s) <span id="numeroConsumo">0</span></small> 
                        </div> 
                        <div class="col-sm-6 text-right">
                            <small class="text-end">Total R$ <span id="totalConsumo"></span></small> 
                        </div>      
                    </div>
                    <hr>
                    <div class="form-row">
                        <div class="col-sm-6">
                            <label for="">Material</label>
                            <select name="produto" class="form-control" id="produto">
                                
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Quantidade</label>
                            <input type="number" step="0" min="0"  value="1" class="form-control" name="quantidade" id="quantidade">
                        </div>
                        <div class="col-sm-4 text-center">
                            <label for="">&nbsp;</label>
                            <button type="submit" name="salvar" id="btnSubmit" class="btn btn-primary Salvar-consumo mt-4"> &#10010; Adicionar consumo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
<!-- editar -->
<!-- editar -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <form action="" id="form" method="POST">
                <div class="modal-body" id="">                               
                    <div class="form-row">
                        <input type="hidden" disabled id="inp_id" >
                        <input type="hidden" disabled id="inp_opcao" value="" >
                        <div class="col-sm-5">
                            <label for="">Data Retirada</label>
                            <input type="text" name="entrada" id="inp_entrada" class="form-control date" value="<?=self::prepareDateBr(date('Y-m-d'))?>">
                        </div>

                        <div class="col-sm-5">
                            <label for="">Data Devolução</label>
                            <input type="text" name="saida" id="inp_saida" class="form-control date" value="<?= self::prepareDateBr(self::addDdayInDate(date('Y-m-d'), 1))?>">
                        </div>

                        <div class="col-sm-2 mt-4">
                            <button class="btn btn-primary mt-2" type="button" id="buscar">
                                <i class="fas fa-search fa-sm"></i>
                            </button>   
                        </div>                    
                    </div>
                    <div class="form-row hide" id="div_apartamento">

                        <div class="col-sm-8">
                            <label for="">Cliente</label><br>
                            <select class="js-example-basic-single form-control" name="cliente" id="inp_cliente">
                                <?php
                                    $clientes = $this->buscaCliente();

                                    if(!empty($clientes)){
                                       foreach ($clientes as $key => $cliente) {
                                            echo '<option value="' . $cliente['id'] . '">' . $cliente['nome'] . '</option>';
                                       }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="">Status</label><br>
                            <select class="form-control" name="status" id="inp_status">
                                <option value="1">Reservada</option>
                                <option value="2">Confirmada</option>
                                <option value="5">Cancelada</option>
                            </select>
                        </div>

                        <div class="col-sm-12">
                            <label for="">Endereço de Entrega</label>
                            <input type="text" class="form-control" name="endereco" value="" id="inp_endereco">
                        </div>

                        <div class="col-sm-4">
                            <label for="">Telefone</label>
                            <input type="tel" maxlength="11" name="telefone" class="form-control" value="" id="inp_telefone">
                        </div>

                        <div class="col-sm-4">
                            <label for="">Responsavel </label>
                            <input type="text" class="form-control" value="" name="responsavel" id="inp_responsavel">
                        </div>

                        <div class="col-sm-4">
                            <label for="">Multa diária %</label>
                            <input type="number" class="form-control" value="2" step="0.01" min="0" name="multa" id="inp_multa">
                        </div>

                        <div class="col-sm-12">
                            <label for="">observação</label><br>
                            <textarea name="observacao" class="form-control" id="inp_observacao" cols="30" rows="3"> &nbsp;</textarea>
                        </div>
                    </div>   

                    <small>
                        <div align="center" class="mt-1" id="mensagem"></div>
                        <div align="right" class="mt-1 fs" id="valores"></div>
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
                    <button type="submit" name="salvar" id="btnSubmitForm" class="btn btn-primary Salvar">Salvar</button>
                </div>
            </form>        
        </div>
        
    </div>
</div>
<!-- editar -->

<!-- editar -->
<div class="modal fade" id="modalHospedadas" tabindex="2" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dados da Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">  
                <input type="hidden" id="id">
                <div class="row">
                    <div class="col-sm-6 text-center mb-2">
                        Cliente: <p id="hospdadas_cliente"></p>
                    </div>
                    <div class="col-sm-3 text-center mb-2">
                        Codigo: <p id="hospdadas_codigo"></p>
                    </div>                    
                    <div class="col-sm-3 text-center mb-2">
                       Multa %: <p id="hospdadas_multa"></p>
                    </div>   
                    <!-- <div class="col-sm-4 text-center mb-2">
                       Lista de Material: <p id="hospdadas_material"></p>
                    </div>                  -->
                    <div class="col-sm-3 text-center mb-2">
                        Data Entrada: <p id="hospdadas_retirada"></p>
                    </div>
                    <div class="col-sm-3 text-center mb-2">
                        Data Saida: <p id="hospdadas_devolucao"></p>
                    </div>                   
                    <div class="col-sm-3 text-center mb-2">
                       Telefone: <p id="hospdadas_telefone"></p>
                    </div>
                    <div class="col-sm-3 text-center mb-2">
                       Responsável: <p id="hospdadas_responsavel"></p>
                    </div>
                    <div class="col-sm-12 text-center mb-2">
                       Endereço: <p id="hospdadas_endereco"></p>
                    </div>
                    <div class="col-sm-12 text-center mb-2">
                        Observação: <p id="hospdadas_observacao"></p>
                    </div>
                    <div class="col-sm-6 text-center mb-2">
                        Consumo: <p id="hospdadas_consumo"></p>
                    </div>
                    <div class="col-sm-6 text-center mb-2">
                        Pagamento: <p id="hospdadas_pagamento"></p>
                    </div>
                   
                </div>
                
                <h6 class="modal-title" id="">Ações</h6>
                <hr>
                <div class="row">
                    <div class="col-sm-3 text-center">
                        <button class="btn btn-primary checkout">Finalizar Reserva</button>
                    </div>
                    <div class="col-sm-3 text-center">
                        <button class="btn btn-success pagamento">+ Pagamento </button>
                    </div>
                    <div class="col-sm-2 text-center">
                        <button class="btn btn-warning consumo">+ Material </button>
                    </div>
                    <div class="col-sm-2 text-center">
                        <button class="btn btn-info editar">Editar</button>
                    </div>
                    <div class="col-sm-2 text-center">
                        <button class="btn btn-secondary imprimir">Imprimir</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<!-- editar -->

<!-- editar -->
<div class="modal fade" id="modalCheckout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Deseja realizar o Check-out</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">  
                <form action="" method="post">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            Cliente: <p id="nomeHospede"></p>
                        </div>
                        <div class="col-sm-3 mb-2">
                            Codigo: <p id="codigoReserva"></p>
                        </div>
                        <div class="col-sm-3 mb-2">
                            Lista de Material: <p id="numeroMaterial"></p>
                        </div>          
                        
                        <div class="col-sm-4 mb-2">
                            Valor: <p id="totalHospedagem"></p>
                        </div>
                        <div class="col-sm-4 mb-2">
                            Valor Pago: <p id="totalPago"></p>
                        </div>
                        <div class="col-sm-4 mb-2">
                            <p id="restante"></p>
                        </div>   
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="button" class="close mr-4" data-dismiss="modal" aria-label="Close">
                                X
                        </button>
                        <button type="button" name="salvar" disabled id="btnSubmit" class="btn btn-primary executar-checkout">Executar</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>


<!-- editar -->
<div class="modal fade" id="modalHospedadasPagamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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


</div>
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
