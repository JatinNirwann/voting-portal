<?php
session_start();
require_once 'processing/config.php';

if (!isset($_SESSION['voter_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote - Voting Portal</title>
    <link rel="stylesheet" href="stylesheet/styles.css">
</head>
<body class="voting-page">
    <nav>
        <div class="logo">Voting Portal</div>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="vote.php">Vote</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="processing/logout.php">Logout</a></li>
            
        </ul>
    </nav>

    <div class="container">
        <form id="vote-form" action="processing/submit_vote.php" method="POST" style="position: relative; left: 280px; top: -37px;">
            <div class="card-container">
                <?php include 'processing/fetch_candidates.php'; ?>
            </div>
            <button type="submit" class="btn-submit" disabled>Submit Vote</button>
        </form>

        <div id="confirmModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
            <div class="login-form" style="width:400px; background:rgba(35, 60, 86, 0.95); padding:30px; border-radius:15px;">
                <h2>Confirm Your Vote</h2>
                <div class="input-container">
                    <label for="voter-id">Voter ID</label>
                    <input type="text" id="voter-id" name="voter-id" required>
                </div>
                <div class="input-container">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button id="confirm-vote-btn">Confirm Vote</button>
            </div>
        </div>
    </div>

    <script>
        const cards = document.querySelectorAll('.card');
        const submitBtn = document.querySelector('.btn-submit');
        const confirmModal = document.getElementById('confirmModal');
        const confirmVoteBtn = document.getElementById('confirm-vote-btn');
        const voterIdInput = document.getElementById('voter-id');
        const passwordInput = document.getElementById('password');
        let selectedCard = null;
        let selectedCandidateId = null;

        cards.forEach(card => {
            card.addEventListener('click', () => {
                if (selectedCard) selectedCard.classList.remove('selected');
                card.classList.add('selected');
                selectedCard = card;
                selectedCandidateId = card.dataset.id;
                submitBtn.disabled = false;
            });
        });

        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            confirmModal.style.display = 'flex';
        });

        confirmVoteBtn.addEventListener('click', function() {
            const voterId = voterIdInput.value;
            const password = passwordInput.value;
            
            if (!voterId || !password) {
                alert('Please enter both Voter ID and Password');
                return;
            }

            const formData = new FormData();
            formData.append('candidate_id', selectedCandidateId);
            formData.append('voter_id', voterId);
            formData.append('password', password);

            fetch('processing/submit_vote.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text);
                    });
                }
                return response.text();
            })
            .then(message => {
                alert(message);
                window.location.href = 'profile.php';
            })
            .catch(error => {
                alert(error.message);
            });
        });
    </script>
</body>
</html>