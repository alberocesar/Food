<?php echo $this->extend('Admin/layout/principal'); ?>


<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<?php echo $this->section('estilos'); ?>



<?php echo $this->endSection(); ?>



<?php echo $this->section('conteudo'); ?>


<div class="row">

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">

            <div class="card-header bg-primary pb-0 pt-4">


                <h4 class="card-title text-white"><?php echo esc($titulo); ?></h4>

            </div>
            <div class="card-body">


                <?php if (session()->has('errors_model')): ?>


                    <ul>

                        <?php foreach (session('errors_model') as $error): ?>

                            <li class="text-danger"><?php echo $error; ?></li>

                        <?php endforeach; ?>

                    </ul>


                <?php endif; ?>



                <?php echo form_open("admin/produtos/cadastrarextras/$produto->id"); ?>

                <div class="form-row">

                    <div class="form-group col-md-6">

                        <label>Escolha o extra do produto (opcional)</label>

                        <select class="form-control" name="extra_id">

                            <option  Escolha.. ></option>

                        <?php foreach ($extras as $extra): ?>

                            <option value="<?php echo $extra->ide ?>"> <?php echo esc($extra->nome); ?> </option>

                        <?php endforeach; ?>    
                            
                        </select>
                                              
                    </div>

                </div>

                <button type="submit" class="btn btn-primary mr-2 btn-sm">
                    <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
                    Inserir Extra
                </button>


                <a href="<?php echo site_url("admin/produtos/show/$produto->id"); ?>" class="btn btn-light text-dark btn-sm">
                    <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
                    Voltar
                </a>


                <?php echo form_close(); ?>
                
                <div class="form-row mt-3">
           
                <div class="col-md-5">

                        <?php if(empty($produtosExtras)): ?>

                            <p>Esse produto não possui extras até o momento</p>

                        <?php else: ?>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


        </div>
    </div>


</div>





<?php echo $this->endSection(); ?>


<?php echo $this->section('scripts'); ?>





<?php echo $this->endSection(); ?>