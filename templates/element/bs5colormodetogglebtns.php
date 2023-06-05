<?= $this->Html->script('SodasHelper.bs5colormodetoggler.min') ?>

<div class="navbar-nav btn-group" role="group" aria-label="Dark theme toggle">
    <input type="radio"
           class="btn-check"
           data-bs-theme-value="light"
           name="darklight"
           id="darklight1"
           autocomplete="off">
    <label class="btn btn-secondary" for="darklight1">
        <i class="far fa-sun"></i>
    </label>

    <input type="radio"
           class="btn-check"
           data-bs-theme-value="dark"
           name="darklight"
           id="darklight2"
           autocomplete="off">
    <label class="btn btn-secondary" for="darklight2">
        <i class="far fa-moon"></i>
    </label>
</div>
<script>
    const themeValue = document.querySelector('html').getAttribute('data-bs-theme');

    const radioInputs = document.querySelectorAll('input[name="darklight"]');
    for (let i = 0; i < radioInputs.length; i++) {
        if (radioInputs[i].getAttribute('data-bs-theme-value') === themeValue) {
            radioInputs[i].checked = true;
            break;
        }
    }
</script>
