<html>
    <head>
        <title>Homepage of Fun Games</title>
    </head>
    <body>
        <h1>THIS IS THE HOMEPAGE</h1>
        <h2>Users:</h2>
        <?php if (!empty($users)): ?>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li><?= htmlspecialchars($user['name']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </body>
</html>