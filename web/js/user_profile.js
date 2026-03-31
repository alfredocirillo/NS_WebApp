// Function to switch between tabs
function openTab(evt, tabName) {
    const contents = document.getElementsByClassName("tab-content");
    const buttons = document.getElementsByClassName("tab-button");

    for (let c of contents) {
        c.style.display = "none";
    }
    for (let b of buttons) {
        b.classList.remove("active");
    }

    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.classList.add("active");
}

// Show first tab by default on page load
document.addEventListener("DOMContentLoaded", () => {
    // Display the first tab content and activate the first tab button
    const firstContent = document.querySelector(".tab-content");
    const firstButton = document.querySelector(".tab-button");
    if (firstContent && firstButton) {
        firstContent.style.display = "block";
        firstButton.classList.add("active");
    }
});

// Function to toggle the profile picture modal visibility
function toggleModal() {
    const modal = document.getElementById("pictureModal");
    if (modal.style.display === "block") {
        modal.style.display = "none";
    } else {
        modal.style.display = "block";
    }
}