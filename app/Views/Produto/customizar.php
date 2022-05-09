<?php echo $this->extend('layout/principal_web'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<?php echo $this->section('estilos'); ?>

<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/produto.css"); ?>"/>

<?php echo $this->endSection(); ?>





<?php echo $this->section('conteudo'); ?>


<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 3em">

    <div class="col-sm-12 col-md-12 col-lg-12">
        <!-- product -->
        <div class="product-content product-wrap clearfix product-deatil">
            <div class="row">

            <h2 class="name">

                <?php echo esc($titulo); ?>

            </h2>

                <?php echo form_open("carrinho/especial"); ?>

                <div class="col-md-12">

                    <?php if (session()->has('errors_model')): ?>


                        <ul style="margin-left: -1.6em !important; list-style: decimal">

                            <?php foreach (session('errors_model') as $error): ?>

                                <li class="text-danger"><?php echo $error; ?></li>

                            <?php endforeach; ?>

                        </ul>


                    <?php endif; ?>
                   
                    <div class="row">


                        <div class="col-sm-4">
                            <input id="btn-adiciona" type="submit" class="btn btn-success btn-block" value="Adicionar">
                        </div>

                        <?php foreach ($especificacoes as $especificacao): ?>

                            <?php if ($especificacao->customizavel): ?>

                                <div class="col-sm-4">
                                    <a href="<?php echo site_url("produto/customizar/$produto->slug"); ?>" class="btn btn-primary btn-block">Customizar</a>
                                </div>

                                <?php break; ?>

                            <?php endif; ?>

                        <?php endforeach; ?>


                        <div class="col-sm-4">
                            <a href="<?php echo site_url("/"); ?>" class="btn btn-info btn-block">Mais delícias</a>
                        </div>


                    </div>
                </div>


                <?php echo form_close(); ?>

            </div>
        </div>
        <!-- end product -->
    </div>


</div>







<?php echo $this->endSection(); ?>




<?php echo $this->section('scripts'); ?>


<script>


    $(document).ready(function () {

        var especificacao_id;

        if (!especificacao_id) {


            $("#btn-adiciona").prop("disabled", true);

            $("#btn-adiciona").prop("value", "Selecione um valor");

        }


        $(".especificacao").on('click', function () {

            especificacao_id = $(this).attr('data-especificacao');

            $("#especificacao_id").val(especificacao_id);


            $("#btn-adiciona").prop("disabled", false);

            $("#btn-adiciona").prop("value", "Adicionar");

        });

        $(".extra").on('click', function () {

            var extra_id = $(this).attr('data-extra');

            $("#extra_id").val(extra_id);

        });

    });




</script>



<?php echo $this->endSection(); ?>