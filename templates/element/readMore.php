<!--Needs Bootstrap 5-->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

<?php if (strlen($text) > 200) { ?>
    <?= $this->Html->css('SodasHelper.readMore') ?>
    <div class="readMore">
        <p class="collapse" id="collapseText_<?= $id ?>" aria-expanded="false">
            <?= $text ?>
        </p>
        <a role="button" class="collapsed" data-bs-toggle="collapse" href="#collapseText_<?= $id ?>" aria-expanded="false" aria-controls="collapseText_<?= $id ?>"></a>
    </div>
<?php } else {
    echo $text;
} ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
