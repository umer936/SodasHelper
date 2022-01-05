<!--Needs Bootstrap 4 or 5-->
<style>
    .toast {
        position: sticky;
        top: <?= $top ?? '0' ?>;
        float: right;
        z-index: 999;
    }
</style>
<?php

// USAGE controller
//echo $this->Flash->render('flash', [
//    'element' => 'SodasHelper.bootstrap_toast',
//    'alertColor' => 'warning',
//])

// USAGE template
//echo $this->element('SodasHelper.bootstrap_toast', [
//    'alertColor' => 'warning',
//    'message' => 'testing',
//    'top' => '60px',
//    'id' => 'abc',
//    'delay' => 10000,
//])
// USAGE fire with example
//<button id="fireABC">FIRE</button>
//<script type="module">
//    document.getElementById('fireABC').addEventListener('click', abcfire);
//</script>

//primary, secondary, success, danger, warning, info, light, dark

/**
 * @var array $params
 * @var string $message
 * @var string $id
 * @var int $delay
 * @var bool $fire
 */
$class = 'message';
if (!empty($params['class'])) {
    if (is_array($params['class'])) {
        array_push($params['class'], $class);
        $class = implode(' ', $params['class']);
    }
    else {
        $class .= ' ' . $params['class'];
    }
}

if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}

if(!isset($id)) {
    $id = uniqid();
}

if(!isset($delay)) {
    $delay = '';
}

?>

<div id="toast<?= $id ?>" data-delay="<?= $delay ?>" class="toast align-items-center text-white bg-<?= $alertColor ?? 'success' ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body">
            <?= $message ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>

<script type="module">
    import { bootstrapVersion } from '<?= $this->Url->script('SodasHelper.sodas_js_scripts') ?>';
    let <?= $id ?>fire;

    const doFire = <?php
        if (isset($fire)) {
            echo 'true';
        } else {
            echo 'false';
        } ?>;
    if (bootstrapVersion.major === 5) {
        <?= $id ?>fire = function() {
            const toast<?= $id ?> = new bootstrap.Toast(document.getElementById('toast<?= $id ?>'));
            toast<?= $id ?>.show();
        }
    } else if (bootstrapVersion.major === 4 && window.jQuery) {
        <?= $id ?>fire = function() {
            $('#toast<?= $id ?>').toast('show');
        }
    }
    if (doFire) <?= $id ?>fire();
    window.<?= $id ?>fire = <?= $id ?>fire;
</script>
