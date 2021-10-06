<!--Needs Bootstrap 5-->
<style>
    .toast {
        position: sticky;
        top: <?= $top ?? '0' ?>;
        float: right;
        z-index: 999;
    }
</style>
<?php

// USAGE
//echo $this->Flash->render('flash', [
//    'element' => 'SodasHelper.bootstrap_toast',
//    'alertColor' => 'warning',
//])

/**
 * @var array $params
 * @var string $message
 * @var string $id
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

//primary, secondary, success, danger, warning, info, light, dark
?>

<div id="toast<?= $id ?>" class="toast align-items-center text-white bg-<?= $alertColor ?? 'success' ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body">
            <?= $message ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>

<script type="module">
    import { bootstrapVersion } from '<?= $this->Url->script('SodasHelper.sodas_js_scripts') ?>';
    if (bootstrapVersion.major === 5) {
        const toast<?= $id ?> = new bootstrap.Toast(document.getElementById('toast<?= $id ?>'));
        toast<?= $id ?>.show();
    } else if (bootstrapVersion.major === 4 && window.jQuery) {
        $('#toast<?= $id ?>').toast('show');
    }
</script>
