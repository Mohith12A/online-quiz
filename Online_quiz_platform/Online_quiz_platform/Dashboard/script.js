// Chart setup
const ctx = document.getElementById('solvingChart').getContext('2d');
let solvingChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Correct Answers', 'Incorrect Answers'],
        datasets: [{
            data: [58, 42],
            backgroundColor: ['green', 'red'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#fff'
                }
            }
        }
    }
});

// Check for screen size and orientation
function checkScreenSize() {
    const width = window.innerWidth;
    const height = window.innerHeight;

    // Adjust layout based on screen dimensions
    if (width < 768) {
        // Mobile layout adjustments
        document.querySelector('.chart-container').style.height = '180px';
    } else {
        // Desktop layout adjustments
        document.querySelector('.chart-container').style.height = '200px';
    }
}

// Run on page load and window resize
window.addEventListener('load', checkScreenSize);
window.addEventListener('resize', checkScreenSize);

// Theme toggle functionality
const themeToggle = document.querySelector('.theme-toggle');
const themeIcon = themeToggle.querySelector('i');
const themeText = themeToggle.querySelector('span');

themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('light-mode');

    if (document.body.classList.contains('light-mode')) {
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
        themeText.textContent = 'Light';

        // Update chart colors for light mode
        solvingChart.options.plugins.legend.labels.color = '#333';
        solvingChart.update();
    } else {
        themeIcon.classList.remove('fa-sun');
        themeIcon.classList.add('fa-moon');
        themeText.textContent = 'Dark';

        // Update chart colors for dark mode
        solvingChart.options.plugins.legend.labels.color = '#fff';
        solvingChart.update();
    }
});

// Fullscreen functionality
document.documentElement.requestFullscreen = document.documentElement.requestFullscreen ||
    document.documentElement.mozRequestFullScreen ||
    document.documentElement.webkitRequestFullScreen ||
    document.documentElement.msRequestFullscreen;

window.onload = function () {
    // Try to request fullscreen on page load
    try {
        document.documentElement.requestFullscreen();
    } catch (e) {
        console.log("Could not enter fullscreen mode:", e);
    }
    
    // Run initial screen size check
    checkScreenSize();
};