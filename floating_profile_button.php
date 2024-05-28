

<div class="floating-profile-button">

    <button class="profile-button" onclick="toggleDropdown()">
        <img src="student_profile_icon.png" alt="Profile" width="20" height="20">
    </button>
    <div class="dropdown-content" id="dropdown">
        <a href="student_view_profile.php">View Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<style>
    .floating-profile-button {
        position: fixed;
        top: 60px;
        right: 20px;
        z-index: 999;
    }

    .profile-button {
        background-color: #4CAF50;
        color: white;
        padding: 20px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);

    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        width: 120px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        border-radius: 0 5px 5px 0;
        left: -130px; /* Adjust the position to align with the button */
        top: 0;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #ddd;
    }
</style>

<script>
    
    function toggleDropdown() {
        var dropdown = document.getElementById("dropdown");
        dropdown.style.display === "block" ? dropdown.style.display = "none" : dropdown.style.display = "block";
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.profile-button')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === "block") {
                    openDropdown.style.display = "none";
                }
            }
        }
    }
</script>
