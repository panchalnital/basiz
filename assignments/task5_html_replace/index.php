<?php
$defaultHtml = '<p align="justify" style="orphans: 0; widows: 0; margin-left: 0.39cm; margin-bottom: 0cm; border: none; padding: 0cm"><b>PARTY2NAME</b><i>, </i>a company incorporated under the laws of ROC2LAW having its Registered Office at P1OFFICEADD. which expression, shall unless it be repugnant to the context or meaning thereof, mean and include its successors and assigns (hereinafter referred to as \'\'Service Provider\') of the ONE PART</p>';

$output = null;
$htmlContent = $_POST['html_content'] ?? $defaultHtml;
$finds = $_POST['find'] ?? ['PARTY2NAME', 'P1OFFICEADD.'];
$replaces = $_POST['replace'] ?? ['Abc india pvt. Ltd.', 'Mount Road,chennai-60014.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Replaces each find[i] with replace[i] inside the given HTML string.
     * Plain str_replace is used so that HTML tags surrounding the
     * placeholder text are left untouched.
     */
    function findReplaceHtml(string $html, array $find, array $replace): string {
        $find = array_filter($find, fn($v) => $v !== '');
        $find = array_values($find);
        $replace = array_values(array_slice($replace, 0, count($find)));
        return str_replace($find, $replace, $html);
    }

    $output = findReplaceHtml($htmlContent, $finds, $replaces);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Task 5 - HTML Find &amp; Replace</title>
<link rel="stylesheet" href="styles.css"/>

</head>
<body>
<div class="wrap">
  <a class="back" href="/basiz/index.php">&larr; Back to assignments</a>
  <div class="card">
    <h1>HTML Content Find &amp; Replace</h1>
    <form method="POST">
      <label>HTML Content</label>
      <textarea name="html_content"><?= htmlspecialchars($htmlContent) ?></textarea>

      <label>Find / Replace Pairs</label>
      <div id="pairsContainer">
        <?php foreach ($finds as $i => $f): ?>
        <div class="pair">
          <input type="text" name="find[]" placeholder="Text to find" value="<?= htmlspecialchars($f) ?>">
          <input type="text" name="replace[]" placeholder="Text to replace" value="<?= htmlspecialchars($replaces[$i] ?? '') ?>">
          <button type="button" onclick="this.parentElement.remove()">Remove</button>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="button" id="addPairBtn" onclick="addPair()">+ Add Pair</button>
      <br>
      <button type="submit">Replace</button>
    </form>
  </div>

  <?php if ($output !== null): ?>
  <div class="card">
    <h1>Output (raw)</h1>
    <div class="output"><?= htmlspecialchars($output) ?></div>
    <h1 style="margin-top:20px;">Rendered</h1>
    <div class="rendered"><?= $output ?></div>
  </div>
  <?php endif; ?>
</div>

<script>
function addPair() {
  const container = document.getElementById('pairsContainer');
  const div = document.createElement('div');
  div.className = 'pair';
  div.innerHTML = `
    <input type="text" name="find[]" placeholder="Text to find">
    <input type="text" name="replace[]" placeholder="Text to replace">
    <button type="button" onclick="this.parentElement.remove()">Remove</button>`;
  container.appendChild(div);
}
</script>
</body>
</html>
