<?php
// Get the current server time
$currentTime = date('H:i:s');

// Format the time into a digital clock format
$digitalClock = date('h:i:s A', strtotime($currentTime));
?>

<style>
.clock-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.clock {
    background-color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style>

<div class="clock-container">
    <div class="clock" id="clock"><?php echo $digitalClock; ?></div>
</div>

<script>
// Function to update the clock every second
function updateClock() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    
    // Format the time into a digital clock format
    var digitalClock = formatTime(hours) + ":" + formatTime(minutes) + ":" + formatTime(seconds);
    
    // Update the clock element with the new time
    document.getElementById('clock').innerHTML = digitalClock;
    
    // Schedule the next update in 1 second
    setTimeout(updateClock, 1000);
}

// Function to add leading zeros to single-digit numbers
function formatTime(time) {
    return time < 10 ? "0" + time : time;
}

// Start updating the clock
updateClock();
</script>
