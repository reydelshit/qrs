<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Custom Video Player</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      background: #111;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .player-container {
      width: 100%;
      max-width: 900px;
      background: #000;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }

    .video-wrapper {
      position: relative;
      width: 100%;
      background: #000;
    }

    video {
      width: 100%;
      display: block;
      background: #000;
    }

    .controls {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px;
      background: #1a1a1a;
      color: #fff;
      flex-wrap: wrap;
    }

    .controls button {
      background: #2b2b2b;
      color: #fff;
      border: none;
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
    }

    .controls button:hover {
      background: #3a3a3a;
    }

    .progress {
      flex: 1;
      min-width: 150px;
      appearance: none;
      height: 6px;
      border-radius: 999px;
      background: #444;
      outline: none;
      cursor: pointer;
    }

    .progress::-webkit-slider-thumb {
      appearance: none;
      width: 14px;
      height: 14px;
      background: #fff;
      border-radius: 50%;
      cursor: pointer;
    }

    .volume {
      width: 100px;
      appearance: none;
      height: 6px;
      border-radius: 999px;
      background: #444;
      outline: none;
      cursor: pointer;
    }

    .volume::-webkit-slider-thumb {
      appearance: none;
      width: 14px;
      height: 14px;
      background: #fff;
      border-radius: 50%;
      cursor: pointer;
    }

    .time {
      min-width: 110px;
      font-size: 14px;
      text-align: center;
    }

    @media (max-width: 600px) {
      .controls {
        gap: 8px;
      }

      .time {
        width: 100%;
        text-align: left;
      }

      .volume {
        width: 80px;
      }
    }
  </style>
</head>
<body>

  <div class="player-container">
    <div class="video-wrapper">
      <video id="video" playsinline></video>
    </div>

    <div class="controls">
      <button id="playPause">Play</button>
      <input type="range" id="progress" class="progress" value="0" min="0" max="100">
      <div class="time" id="timeDisplay">00:00 / 00:00</div>
      <input type="range" id="volume" class="volume" min="0" max="1" step="0.01" value="1">
      <button id="fullscreen">Fullscreen</button>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
  <script>
    const video = document.getElementById("video");
    const playPauseBtn = document.getElementById("playPause");
    const progress = document.getElementById("progress");
    const volume = document.getElementById("volume");
    const fullscreenBtn = document.getElementById("fullscreen");
    const timeDisplay = document.getElementById("timeDisplay");

    // ILISI NI SA IMONG VIDEO LINK
    const videoSrc = "https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8";
    // Example MP4:
    // const videoSrc = "https://www.w3schools.com/html/mov_bbb.mp4";

    function loadVideo(src) {
      if (src.endsWith(".m3u8")) {
        if (Hls.isSupported()) {
          const hls = new Hls();
          hls.loadSource(src);
          hls.attachMedia(video);
        } else if (video.canPlayType("application/vnd.apple.mpegurl")) {
          video.src = src;
        } else {
          alert("HLS is not supported in this browser.");
        }
      } else {
        video.src = src;
      }
    }

    loadVideo(videoSrc);

    playPauseBtn.addEventListener("click", () => {
      if (video.paused) {
        video.play();
        playPauseBtn.textContent = "Pause";
      } else {
        video.pause();
        playPauseBtn.textContent = "Play";
      }
    });

    video.addEventListener("play", () => {
      playPauseBtn.textContent = "Pause";
    });

    video.addEventListener("pause", () => {
      playPauseBtn.textContent = "Play";
    });

    video.addEventListener("timeupdate", () => {
      if (video.duration) {
        progress.value = (video.currentTime / video.duration) * 100;
        timeDisplay.textContent = `${formatTime(video.currentTime)} / ${formatTime(video.duration)}`;
      }
    });

    progress.addEventListener("input", () => {
      if (video.duration) {
        video.currentTime = (progress.value / 100) * video.duration;
      }
    });

    volume.addEventListener("input", () => {
      video.volume = volume.value;
    });

    fullscreenBtn.addEventListener("click", () => {
      const container = document.querySelector(".player-container");
      if (container.requestFullscreen) {
        container.requestFullscreen();
      } else if (container.webkitRequestFullscreen) {
        container.webkitRequestFullscreen();
      } else if (container.msRequestFullscreen) {
        container.msRequestFullscreen();
      }
    });

    function formatTime(seconds) {
      if (isNaN(seconds)) return "00:00";

      const mins = Math.floor(seconds / 60);
      const secs = Math.floor(seconds % 60);

      return `${String(mins).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
    }
  </script>

</body>
</html>