<div class="row">

    <div class="form-group col-md-4">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" value="<?php echo old('nome', esc($categoria->nome)); ?>">
    </div>

</div>
<?php if ($categoria->id): ?>

        <div class="form-check form-check-flat form-check-primary mb-2">
            <label for="ativo" class="form-check-label">

                <input type="hidden" name="ativo" value="0">
                <input type="checkbox" class="form-check-input" id="ativo" name="ativo" value="1" <?php if (old('ativo', $categoria->ativo == 't')): ?> checked="" <?php endif; ?> >
                Ativo
            </label>
        </div>
        



<?php endif; ?> 

<button type="submit" class="btn btn-primary mr-2 btn-sm">
    <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
    Salvar
</button>
