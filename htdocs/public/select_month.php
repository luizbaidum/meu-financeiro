<?php 
    $mes_selecionado = $_POST['mesFiltro'] ?? date('M');
    $months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Todos'); 
?>

<div class="form-group m-2">
	<div class="row">
        <div class="col-6">
            <label for="idMesFiltro">Mês</label>
            <select class="form-select" id="idMesFiltro" name="mesFiltro">
                <?php foreach ($months as $v): ?>
                    <option value="<?= $v; ?>"<?= ($v == $mes_selecionado ? ' selected ' : ''); ?>><?= $v; ?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
</div>