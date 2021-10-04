<!--Needs Bootstrap 5-->

<?php

echo $this->Html->script('SodasHelper.bootstrap_detect_or_load');

// USAGE
//echo $this->element('SodasHelper.read_more', ['id' => 'test',
//    'text' => 'Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text ',
//])

if (!empty($params['class'])) {
    $class = ' ' . $params['class'];
}

?>

<?php if (strlen($text) > 200) { ?>
    <?= $this->Html->css('SodasHelper.read_more') ?>
    <div class="readMore">
        <p class="collapse" id="collapseText_<?= $id ?>" aria-expanded="false">
            <?= $text ?>
        </p>
        <a role="button" class="collapsed<?= $class ?? '' ?>" data-bs-toggle="collapse" href="#collapseText_<?= $id ?>" aria-expanded="false" aria-controls="collapseText_<?= $id ?>"></a>
    </div>
<?php } else {
    echo $text;
} ?>
