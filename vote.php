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
            <li><a href="profile.php">Profile</a></li>
            <li><a href="processing/logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <form id="vote-form" action="processing/submit_vote.php" method="POST" style="position: relative; left: 322px; top: -35px;">
            <div class="card-container">
                <?php include 'processing/fetch_candidates.php'; ?>
            </div>
            <button type="submit" class="btn-submit" disabled>Submit Vote</button>
        </form>
    </div>

    <script>
        const cards = document.querySelectorAll('.card');
        const submitBtn = document.querySelector('.btn-submit');
        let selectedCard = null;

        cards.forEach(card => {
            card.addEventListener('click', () => {
                if (selectedCard) selectedCard.classList.remove('selected');
                card.classList.add('selected');
                selectedCard = card;
                submitBtn.disabled = false;
                document.querySelector('#vote-form').action = `processing/submit_vote.php?id=${card.dataset.id}`;
            });
        });
    </script>
</body>
</html>
