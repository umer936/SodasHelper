<?php
/**
 * @var \App\View\AppView $this
 */
?>

<section class="section-tile">
    <h1>Logs Folder</h1>

    <?php if (!empty($files)): ?>
        <ul>
            <?php foreach ($files as $file): ?>
                <li>
                    <h2><?= $file ?></h2>
                    <?php
                    $filePath = $logFolder . $file;
                    // Display file contents with syntax highlighting
                    $fileContents = file_get_contents($filePath);
                    if ($fileContents !== false): ?>
                        <div style="max-height: 400px; overflow: auto;">
                            <pre><code><?= htmlspecialchars($fileContents) ?></code></pre>
                        </div>
                    <?php else: ?>
                        <p>Error: Unable to read file contents.</p>
                    <?php endif; ?>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No files found in the logs folder.</p>
    <?php endif; ?>
</section>
