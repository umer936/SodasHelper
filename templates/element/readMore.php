<?php if (strlen($text) > 200) { ?>
    <?= $this->Html->css('readMore') ?>
    <div class="readMore">
        <p class="collapse" id="collapseText_<?= $id ?>" aria-expanded="false">
            <?= $text ?>
        </p>
        <a role="button" class="collapsed" data-toggle="collapse" href="#collapseText_<?= $id ?>" aria-expanded="false" aria-controls="collapseText_<?= $id ?>"></a>
    </div>
<?php } else {
    echo $text;
} ?>
