<?php
$defaultTemplate = "@Name@ ipsum dolor sit amet, consectetur adipiscing elit. Praesent in mollis magna. Donec eu elit pellentesque, maximus nisl vitae, cursus velit. Sed Loremnibh sed elit auctor tincidunt. Donec tempor est id nunc ullamcorper rhoncus. Phasellus nec arcu et dui varius ullamcorper commodo quis ligula. Etiam ultrices nisi @email@, ut euismod elit tempor sed. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque auctor turpis vel nisi fermentum, a sodales magna egestas. Vestibulum lobortis elit sed neque rhoncus, sit amet @mobile@ magna blandit. @designation@ nec leo ac diam euismod fringilla.";

$output = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $template    = $_POST['template'] ?? '';
    $name        = trim($_POST['name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $mobile      = trim($_POST['mobile'] ?? '');
    $designation = trim($_POST['designation'] ?? '');

    /**
     * Replaces @Name@, @email@, @mobile@, @designation@ placeholders
     * in a template string with the supplied values.
     */
    function replacePlaceholders(string $template, array $values): string {
        $search  = array_map(fn($k) => '@' . $k . '@', array_keys($values));
        $replace = array_values($values);
        return str_replace($search, $replace, $template);
    }

    $output = replacePlaceholders($template, [
        'Name'        => $name,
        'email'       => $email,
        'mobile'      => $mobile,
        'designation' => $designation,
    ]);
} else {
    $template = $defaultTemplate;
    $name = 'RRRR';
    $email = 'RRR@RRR.com';
    $mobile = '9988775566';
    $designation = 'Software Developer.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Task 3 - Placeholder Replace</title>
<link rel="stylesheet" href="styles.css"/>

</head>
<body>
<div class="wrap">
  <a class="back" href="/basiz/index.php">&larr; Back to assignments</a>
  <div class="card">
    <h1>Placeholder String Replace</h1>
    <form method="POST">
      <label>Template string (use @Name@, @email@, @mobile@, @designation@)</label>
      <textarea name="template"><?= htmlspecialchars($template) ?></textarea>

      <div class="grid">
        <div>
          <label>Name</label>
          <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
        </div>
        <div>
          <label>Email</label>
          <input type="text" name="email" value="<?= htmlspecialchars($email) ?>">
        </div>
        <div>
          <label>Mobile</label>
          <input type="text" name="mobile" value="<?= htmlspecialchars($mobile) ?>">
        </div>
        <div>
          <label>Designation</label>
          <input type="text" name="designation" value="<?= htmlspecialchars($designation) ?>">
        </div>
      </div>
      <button type="submit">Replace</button>
    </form>
  </div>

  <?php if ($output !== null): ?>
  <div class="card">
    <h1>Output</h1>
    <div class="output"><?= htmlspecialchars($output) ?></div>
  </div>
  <?php endif; ?>
</div>
</body>
</html>
