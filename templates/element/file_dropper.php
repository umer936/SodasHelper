<?php

// 'options' are passed to HTML file input element
/**
 USAGE EXAMPLES
    echo $this->element('SodasHelper.file_dropper');
    echo $this->element('SodasHelper.file_dropper', [
        'name' => 'filename',
        'label' => 'second',
        'options' => [
            'multiple' => true,
            'accept' => 'image/png, .xls',
        ],
    ]);
    echo $this->element('SodasHelper.file_dropper', [
        'name' => 'filename2',
        'label' => false,
        'options' => [
            'multiple' => true,
            'accept' => 'image/png, .xls',
        ],
    ]);
 **/

    $label = $label ?? 'Choose a file to upload:';
    $options = array_merge($options ?? [], ['type' => 'file', 'label' => false, 'class' => 'fileDrop']);
    $name =  $name ?? 'filename';
?>
<div id="<?= $name ?>Upload" class="fileDropBox">
    <?= $this->Form->label($name, $label) ?>
    <?= $this->Form->control($id ?? $name, $options) ?>
    <p id="<?= $name ?>Text" class="fileDropText">
        <?= $dropzoneText ?? '
        <i class="fa fa-upload" aria-hidden="true"></i>
        Drag your files here or click this area to select files</p>' ?>
</div>


<script type="module">
    const filePicker = document.querySelector('#<?= $name ?>');
    filePicker.addEventListener('change', function () {
        let files = [];
        Array.from(this.files).forEach(file => {
            files.push(' ' + file.name);
        });
        let plural = 's';
        if (this.files.length === 1) {
            plural = '';
        }
        document.querySelector('#<?= $name ?>Text').textContent =
            this.files.length + " file" + plural + " selected:" + files;
    });

    const dropZone = document.querySelector('#<?= $name ?>Upload');
    const filenameText = document.querySelector('#<?= $name ?>Text');
    dropZone.addEventListener('dragover', function () {
        filenameText.classList.add('active');
        filenameText.textContent = "Release to select files";
    });
    dropZone.addEventListener('dragleave', function () {
        filenameText.classList.remove('active');
        filenameText.textContent = "Drag & Drop or click this area to select files";
    });
</script>


<?= $this->Html->css('SodasHelper.file_dropper') ?>
