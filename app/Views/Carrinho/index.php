<?php echo $this->extend('layout/principal_web'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<?php echo $this->section('estilos'); ?>

<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/produto.css"); ?>" />

<?php echo $this->endSection(); ?>





<?php echo $this->section('conteudo'); ?>


<div class="container-fluid section" id="menu" data-aos="fade-up" style="margin-top: 3em">

    <div class="col-sm-12 col-md-12 col-lg-12">
        <!-- product -->
        <div class="product-content product-wrap clearfix product-deatil">
            <div class row>

                <?php if (!isset($carrinho)) : ?>

                    <div class="text-center">

                        <h2 class="text-center">Seu carrinho de compras está vazio</h2>

                        <a href="<?php echo site_url("/"); ?>" class="btn btn-lg" style="background-color: #990100; color: white; margin-top: 2em">Mais delícias</a>

                    </div>

                <?php else : ?>



                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">remover</th>
                                <th scope="col">produto</th>
                                <th scope="col">tamanho</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">preço</th>
                                <th scope="col">sub total</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $total = 0; ?>

                            <?php foreach ($carrinho as $produto) : ?>

                                <tr>
                                    <th class="text-certer" scope="row">
                                        <a class="btn btn-danger btn-sm" href="<?php echo site_url("carrinho/remover/$produto->slug"); ?>">X</a>

                                    </th>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                </tr>

                            <?php endforeach; ?>


                        </tbody>
                    </table>

                <?php endif; ?>

            </div>
        </div>
        <!-- end product -->
    </div>


</div>



<?php echo $this->endSection(); ?>


<?php echo $this->section('scripts'); ?>


<script>








</script>



<?php echo $this->endSection(); ?>