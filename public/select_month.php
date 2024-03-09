<?php 
    $mes_selecionado = $_POST["mesFiltro"] ?? str_replace(0, "", date("m"));
    $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Todos"); 
?>

<div class="form-group m-2">
	<div class="row">
        <div class="col-6">
            <label for="idMesFiltro">Mês</label>
            <select class="form-select" id="idMesFiltro" name="mesFiltro">
                <option value="">Atual</option>
                    <?php foreach ($months as $k => $v): ?>
                        <option value="<?= ($k + 1); ?>"<?= (($k + 1) == $mes_selecionado ? "selected" : ""); ?>><?= $v; ?></option>
                    <?php endforeach;?>
            </select>
        </div>
    </div>
</div>