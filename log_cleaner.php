<?php

// Log Cleaner - Automatizza la pulizia di file di log obsoleti
function cleanOldLogs(string $logDir, int $days = 30, string $extension = 'log', bool $silent = false): void {
    if (!is_dir($logDir) || !is_readable($logDir)) {
        throw new InvalidArgumentException("Directory non valida o non leggibile: $logDir");
    }

    $files = glob($logDir . '/*.' . $extension);
    $now = time();
    $logReport = __DIR__ . '/cleaner_' . date('Y-m-d') . '.log';

    if (!$silent) {
        echo "Inizio pulizia dei log nella directory: $logDir\n";
    }

    foreach ($files as $file) {
        if (realpath($file) === false || !is_file($file)) {
            if (!$silent) {
                echo "File sospetto ignorato: $file\n";
            }
            file_put_contents($logReport, "File sospetto ignorato: $file\n", FILE_APPEND);
            continue;
        }

        if (($now - filemtime($file)) > ($days * 86400)) {
            if (!unlink($file)) {
                if (!$silent) {
                    echo "Errore durante l'eliminazione del file: $file\n";
                }
                file_put_contents($logReport, "Errore durante l'eliminazione del file: $file\n", FILE_APPEND);
            } else {
                if (!$silent) {
                    echo "Log eliminato: $file\n";
                }
                file_put_contents($logReport, "Log eliminato: $file\n", FILE_APPEND);
            }
        }
    }

    if (!$silent) {
        echo "Pulizia completata.\n";
    }
}
?>
