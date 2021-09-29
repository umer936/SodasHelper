<!--Needs Bootstrap 5-->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

<script>
    const toast<?= $id ?> = new bootstrap.Toast(document.getElementById('toast<?= $id ?>'));
    toast<?= $id ?>.show();
</script>
