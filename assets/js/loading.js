document.addEventListener("DOMContentLoaded", function() {
    var progressBar = document.getElementById("progress-bar");
    var progress = 0;
    var interval = setInterval(function() {
        progress += 1;
        progressBar.style.width = progress + "%";
        if (progress >= 100) {
            clearInterval(interval);
            setTimeout(function() {
                var loadingContainer = document.getElementById("loading-container");
                loadingContainer.style.display = "none";
            }, 500);
        }
    }, 30);
});