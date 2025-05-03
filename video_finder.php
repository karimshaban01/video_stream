<?php
class VideoFinder {
    private $videoExtensions = ['mp4', 'mkv', 'avi', 'mov', 'wmv', 'flv', 'webm'];
    
    public function findVideos($directory) {
        $videos = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = strtolower(pathinfo($file->getPathname(), PATHINFO_EXTENSION));
                if (in_array($extension, $this->videoExtensions)) {
                    $videos[] = [
                        'name' => $file->getFilename(),
                        'path' => str_replace('/var/www/html/', '', $file->getPathname()),
                        'size' => $this->formatFileSize($file->getSize()),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime())
                    ];
                }
            }
        }
        return $videos;
    }

    private function formatFileSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
?>
