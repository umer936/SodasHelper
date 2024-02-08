<?php
namespace SodasHelper\Panel;

use DebugKit\DebugPanel;

class LogsFolderPanel extends DebugPanel
{
    public string $plugin = 'SodasHelper';
    public array $files = [];

    public function shutdown(\Cake\Event\EventInterface $event): void
    {
        $logFolder = ROOT . DS . 'logs' . DS;     
        $files = scandir($logFolder);

        // Filter out '.' and '..' entries
        $this->files = array_diff($files, ['.', '..']);

        $this->_data = ['files' => $this->files, 'logFolder' => $logFolder];
    }

    public function data(): array
    {
        return $this->_data;
    }

    public function summary(): string
    {
        // Count the number of files
        return strval(count($this->files));
    }
}
?>
