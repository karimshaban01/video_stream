<?php
    require_once 'video_finder.php';
    
    $finder = new VideoFinder();
    $videos = $finder->findVideos('/var/www/html/movie');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Stream</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #1a1a1a;
            color: #fff;
        }
        video {
            position: fixed;
            top: 0;
            left: 0;
            width: 70%;
            height: 100vh;
            background: #000;
            border-right: 2px solid #333;
            object-fit: contain; /* Make video fit properly */
        }
        @media (max-width: 768px) {
            video {
                width: 100%;
                height: 50vh;
            }
            .video-list {
                width: 100%;
                height: 50vh;
                top: 50vh;
            }
        }
        .video-list {
            width: 30%;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            padding: 20px;
            background-color: #1f1f1f;
            overflow-y: auto;
        }
        .video-list::-webkit-scrollbar {
            width: 8px;
        }
        .video-list::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 4px;
        }
        ul {
            list-style: none;
        }
        .card {
            width: 100%;
            background-color: #2d2d2d;
            margin-bottom: 10px;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .card:hover {
            transform: translateX(5px);
            background-color: #3d3d3d;
        }
        .video-name {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }
        .download-btn {
            color: #888;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            transition: color 0.2s;
            font-size: 16px;
        }
        .download-btn:hover {
            color: #fff;
        }
        li {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            font-size: 14px;
        }
        li::before {
            content: '▶';
            margin-right: 10px;
            color: #666;
        }
        .video-info {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        .now-playing {
            background-color: #444;
        }
    </style>
</head>
<body>
    <video id="video_player" src="" controls autoplay></video>
    <div class="video-list">
        <ul>
            <?php 
                foreach ($videos as $video) {
                    echo '<div class="card" onclick="handleClick(event, \'' . $video['path'] . '\', this)">
                            <li>
                                <span class="video-name" title="' . htmlspecialchars($video['name']) . '">' . 
                                    htmlspecialchars($video['name']) . 
                                '</span>
                                <div class="video-info">
                                    Size: ' . $video['size'] . ' | Modified: ' . $video['modified'] . '
                                </div>
                            </li>
                            <a href="' . $video['path'] . '" download class="download-btn" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                          </div>';
                }
            ?>
        </ul>
    </div>

    <script>
        function player(videoPath, element) {
            const videoPlayer = document.getElementById("video_player");
            videoPlayer.src = videoPath;
            videoPlayer.play();
            
            document.querySelectorAll('.card').forEach(card => {
                card.classList.remove('now-playing');
            });
            element.classList.add('now-playing');
        }

        function handleClick(event, videoPath, element) {
            if (event.target.closest('.download-btn')) {
                event.stopPropagation();
                return;
            }
            player(videoPath, element);
        }
    </script>
</body>
</html>