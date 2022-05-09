<?php echo $this->extend('layout/principal_web'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<?php echo $this->section('estilos'); ?>

<<<<<<< HEAD
<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/produto.css"); ?>"/>
=======
<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/produto.css"); ?>" />
>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682

<?php echo $this->endSection(); ?>





<?php echo $this->section('conteudo'); ?>


<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 3em">

    <div class="col-sm-8 col-md-offset-2">
        <!-- product -->
        <div class="product-content product-wrap clearfix product-deatil">
            <div class="row">


                <h2 class="name">

                    <?php echo esc($titulo); ?>

                </h2>


                <?php echo form_open("carrinho/especial"); ?>


                <div class="row">


                    <div class="col-md-12" style="margin-top: 1em; margin-bottom: 2em">

<<<<<<< HEAD
                        <?php if (session()->has('errors_model')): ?>
=======
                        <?php if (session()->has('errors_model')) : ?>
>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682


                            <ul style="margin-left: -1.6em !important; list-style: decimal">

<<<<<<< HEAD
                                <?php foreach (session('errors_model') as $error): ?>
=======
                                <?php foreach (session('errors_model') as $error) : ?>
>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682

                                    <li class="text-danger"><?php echo $error; ?></li>

                                <?php endforeach; ?>

                            </ul>

<<<<<<< HEAD

                        <?php endif; ?>



=======
                        <?php endif; ?>

>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682
                    </div>


                    <div class="col-md-6">

<<<<<<< HEAD

                        <div id="imagemPrimeiroProduto" style="margin-bottom: 1em">


                            <img class="img-responsive center-block d-block mx-auto" src="<?php echo site_url("web/src/assets/img/escolha_produto.jpg"); ?>" width="200" alt="Escolha o produto"/>


=======
                        <div id="imagemPrimeiroProduto" style="margin-bottom: 1em">

                            <img class="img-responsive center-block d-block mx-auto" src="<?php echo site_url("web/src/assets/img/escolha_produto.jpg"); ?>" width="200" alt="Escolha o produto" />
>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682

                        </div>


                        <label>Escolha a primeira metade do produto</label>

                        <select id="primeira_metade" class="form-control" name="primeira_metade">

<<<<<<< HEAD
                            <option value="">Escolha seu produto...</option>
=======
                            <option>Escolha Seu produto</option>
>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682

                            <?php foreach ($opcoes as $opcao): ?>

                                <option value="<?php echo $opcao->id; ?>"><?php echo esc($opcao->nome); ?></option>

<<<<<<< HEAD
                            <?php endforeach; ?>


                        </select>



                    </div>


=======
                            <?php endforeach; ?>    

                        </select>

                    </div>

>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682
                    <div class="col-md-6">

                        <div id="imagemSegundoProduto" style="margin-bottom: 1em">

<<<<<<< HEAD
                            <img class="img-responsive center-block d-block mx-auto" src="<?php echo site_url("web/src/assets/img/escolha_produto.jpg"); ?>" width="200" alt="Escolha o produto"/>

                        </div>


=======
                            <img class="img-responsive center-block d-block mx-auto" src="<?php echo site_url("web/src/assets/img/escolha_produto.jpg"); ?>" width="200" alt="Escolha o produto" />

                        </div>

>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682
                        <label> Escolha a segunda metade</label>

                        <select id="segunda_metade" class="form-control" name="segunda_metade">

                            <!-- Aqui serão renderizas as opções para compor a segunda metade, via javascrit -->

                        </select>

                    </div>

<<<<<<< HEAD





=======
>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682
                </div>


                <div class="row">


                    <div class="col-md-6">

                        <div id="valor_produto_customizado" style="margin-top: 1.5em; font-size: 28px; color: #990100; font-family: 'Montserrat-Bold';">


                            <!-- Aqui será renderizado, via javascript, o valor do produto -->


                        </div>

                    </div>

                </div>





                <div class="row" style="margin-top: 3em; margin-bottom: 3em">

                    <div class="col-md-6">

                        <label>Tamanho do produto</label>

                        <select id="tamanho" class="form-control" name="tamanho">

                            <!-- Aqui serão renderizas as opções de tamanho, via javascrit -->

                        </select>

                    </div>


                    <div class="col-md-6">

                        <div id="boxInfoExtras" style="display: none">

                            <label>Extras</label>


                            <div class="radio"><label><input type="radio" class="extra" name="extra" checked="">Sem extra</label></div>


                            <div id="extras">

<<<<<<< HEAD

                                <!-- Aqui serão renderizados os extras do produto, via javascript -->



                            </div>


=======
                                <!-- Aqui serão renderizados os extras do produto, via javascript -->

                            </div>

>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682
                        </div>


                    </div>



                </div>

                <div>

                    <input type="hidden" id="extra_id" name="extra_id" placeholder="extra_id_hidden">

                </div>


                <div class="row">


                    <div class="col-sm-3">
                        <input id="btn-adiciona" type="submit" class="btn btn-success" value="Adicionar">
                    </div>

                    <div class="col-sm-3">
                        <a href="<?php echo site_url("produto/detalhes/$produto->slug"); ?>" class="btn btn-info">Voltar</a>
                    </div>


                </div>


                <?php echo form_close(); ?>

            </div>
        </div>
        <!-- end product -->
    </div>


</div>





<<<<<<< HEAD
=======

>>>>>>> c95530ab4d9bdcba23d152971c531d27a9e59682
<?php echo $this->endSection(); ?>




<?php echo $this->section('scripts'); ?>


<script>
    $(document).ready(function() {

        var especificacao_id;

        if (!especificacao_id) {


            $("#btn-adiciona").prop("disabled", true);

            $("#btn-adiciona").prop("value", "Selecione um valor");

        }


        $(".especificacao").on('click', function() {

            especificacao_id = $(this).attr('data-especificacao');

            $("#especificacao_id").val(especificacao_id);


            $("#btn-adiciona").prop("disabled", false);

            $("#btn-adiciona").prop("value", "Adicionar");

        });

        $(".extra").on('click', function() {

            var extra_id = $(this).attr('data-extra');

            $("#extra_id").val(extra_id);

        });

    });
</script>



<?php echo $this->endSection(); ?>