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
  <style>
    .card-container {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      width: 80%;
      max-width: 1000px;
      margin: 100px auto 20px;
    }

    .card {
      background-color: rgba(35, 60, 86, 0.95);
      border: 2px solid transparent;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      cursor: pointer;
      transition: transform 0.3s, border-color 0.3s;
      padding: 10px;
    }

    .card p {
      color: white;
      font-size: 1em;
      font-weight: bold;
      margin-top: 10px;
    }

    .card.selected {
      border-color: #44bd32;
      transform: scale(1.05);
    }

    .submit-button {
      background: linear-gradient(to right, #44bd32, #38a92c);
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-size: 1.1em;
      cursor: pointer;
      margin: 20px auto;
      display: block;
      max-width: 200px;
      transition: all 0.3s ease;
      text-align: center;
    }

    .submit-button:hover {
      background: linear-gradient(to right, #38a92c, #2d8a23);
      transform: scale(1.02);
    }

    .message {
      text-align: center;
      font-size: 1.2em;
      color: red;
    }
  </style>
</head>

<body class="voting-page">
  <nav>
    <div class="logo">Voting Portal</div>
    <ul>
      <li><a href="index.php">Login</a></li>
      <li><a href="registration.php">Register</a></li>
    </ul>
  </nav>

  <div class="card-container">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "voting_portal";

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
