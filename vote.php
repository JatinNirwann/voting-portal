<?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Voting Portal - Select a Candidate</title>
</head>

<body class="voting-page">
  <nav>
    <div class="logo">Voting Portal</div>
    <ul>
      <li><a href="profile.php">Profile</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <div class="card-container">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "testing_voting_portal";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("<p class='message'>Database connection failed: " . $conn->connect_error . "</p>");
    }

    // Assume the logged-in voter ID is passed via session or other mechanism
    session_start();
    $voterid = $_SESSION['voterid'] ?? null;

    if ($voterid) {
      // Get district code of the logged-in voter
      $sql_voter = "SELECT district_codes FROM voters WHERE voterid='$voterid'";
      $result_voter = $conn->query($sql_voter);

      if ($result_voter->num_rows > 0) {
        $voter_data = $result_voter->fetch_assoc();
        $district_code = $voter_data['district_codes'];

        // Fetch candidates from the same district
        $sql_candidates = "SELECT id, name, age, party FROM candidates WHERE district_code='$district_code'";
        $result_candidates = $conn->query($sql_candidates);

        if ($result_candidates->num_rows > 0) {
          while ($candidate = $result_candidates->fetch_assoc()) {
            $candidateName = htmlspecialchars($candidate['name']);
            $candidateAge = htmlspecialchars($candidate['age']);
            $candidateParty = htmlspecialchars($candidate['party']);
            $candidateId = htmlspecialchars($candidate['id']);
            echo "
              <div class='card' onclick='selectCard(this)' data-id='$candidateId'>
                <p>$candidateName</p>
                <p>Age: $candidateAge</p>
                <p>Party: $candidateParty</p>
              </div>
            ";
          }
        } else {
          echo "<p class='message'>No candidates found in your district.</p>";
        }
      } else {
        echo "<p class='message'>Invalid voter ID.</p>";
      }
    } else {
      echo "<p class='message'>Please log in to view candidates.</p>";
    }

    $conn->close();
    ?>
  </div>

  <button class="submit-button" onclick="submitSelection()">Submit</button>

  <script>
    let selectedCardId = null;

    function selectCard(card) {
      document.querySelectorAll('.card').forEach(card => card.classList.remove('selected'));
      card.classList.add('selected');
      selectedCardId = card.getAttribute('data-id');
    }

    function submitSelection() {
      if (!selectedCardId) {
        alert("Please select a candidate before submitting.");
      } else if (confirm("Are you sure you want to submit your vote?")) {
        // Here, you can send the selected candidate ID to a PHP script to save the vote
        fetch('submit_vote.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ candidateId: selectedCardId })
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert("Vote submitted successfully!");
              window.location.href = 'success_page.php';
            } else {
              alert("Error submitting your vote. Please try again.");
            }
          })
          .catch(error => {
            console.error("Error:", error);
          });
      }
    }
  </script>
</body>

</html>
