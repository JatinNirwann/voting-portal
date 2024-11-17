<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Voting Portal - Select an Option</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    /* Additional Styles for the Cards Page */
    .card-container {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
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

    .card img {
      width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 8px;
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

    /* Submit Button Style */
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
  </style>
</head>

<body class="index-page">
  <nav>
    <div class="logo">Voting Portal</div>
    <ul>
      <li><a href="index.php">Login</a></li>
      <li><a href="registration.php">Register</a></li>
    </ul>
  </nav>

  <div class="card-container">
    <div class="card" onclick="selectCard(this)">
      <img src="images/parties/AAP.png" alt="AAP">
      <p>Aam Aadmi Party (AAP)</p>
    </div>
    <div class="card" onclick="selectCard(this)">
      <img src="images/parties/AITC.png" alt="AITC">
      <p>All India Trinamool Congress (AITC)</p>
    </div>
    <div class="card" onclick="selectCard(this)">
      <img src="images/parties/BJP.png" alt="BJP">
      <p>Bharatiya Janata Party (BJP)</p>
    </div>
    <div class="card" onclick="selectCard(this)">
      <img src="images/parties/BSP.png" alt="BSP">
      <p>Bahujan Samaj Party (BSP)</p>
    </div>
    <div class="card" onclick="selectCard(this)">
      <img src="images/parties/CPI.png" alt="CPI">
      <p>Communist Party of India (CPI)</p>
    </div>
    <div class="card" onclick="selectCard(this)">
      <img src="images/parties/DMK.png" alt="DMK">
      <p>Dravida Munnetra Kazhagam (DMK)</p>
    </div>
    <div class="card" onclick="selectCard(this)">
      <img src="images/parties/INC.png" alt="INC">
      <p>Indian National Congress (INC)</p>
    </div>
    <div class="card" onclick="selectCard(this)">
      <img src="images/parties/JD.png" alt="JD">
      <p>Janata Dal (JD)</p>
    </div>
    <div class="card" onclick="selectCard(this)">
      <img src="images/parties/NCP.png" alt="NCP">
      <p>Nationalist Congress Party (NCP)</p>
    </div>
    <div class="card" onclick="selectCard(this)">
      <img src="images/parties/SP.png" alt="SP">
      <p>Samajwadi Party (SP)</p>
    </div>
  </div>


  <button class="submit-button" onclick="submitSelection()">Submit</button>

  <script>
    let selectedCard = null;

    function selectCard(card) {
      document.querySelectorAll('.card').forEach(card => card.classList.remove('selected'));
      card.classList.add('selected');
      selectedCard = card.querySelector('p').innerText; // Get the name of the selected card
    }

    function submitSelection() {
      if (!selectedCard) {
        alert("Please select an option before submitting.");
      } else if (confirm(`Are you sure you want to submit your vote for "${selectedCard}"?`)) {
        alert("Vote submitted successfully!");
      }
    }
  </script>
</body>

</html>