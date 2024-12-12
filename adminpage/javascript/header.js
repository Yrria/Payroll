 // Function to update date and time
 function updateDateTime() {
    const now = new Date();

    // Format date
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    const dateString = now.toLocaleDateString(undefined, options);

    // Format time
    const timeString = now.toLocaleTimeString();

    // Insert date and time into the header
    document.getElementById('current-date').innerText = dateString;
    document.getElementById('current-time').innerText = timeString;
}

// Call the function once when the page loads
updateDateTime();

// Update the time every second
setInterval(updateDateTime, 1000);