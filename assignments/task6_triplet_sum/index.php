<?php

/**
 * Finds a triplet in $arr whose sum equals $target.
 * Uses sort + two-pointer technique: O(n^2) time, O(n) space (sorted copy).
 * Returns the triplet as an array on success, or false if none exists.
 */
function findTripletSum(array $arr, int $target) {
    $n = count($arr);
    $sorted = $arr;
    sort($sorted);

    for ($i = 0; $i < $n - 2; $i++) {
        $left = $i + 1;
        $right = $n - 1;

        while ($left < $right) {
            $sum = $sorted[$i] + $sorted[$left] + $sorted[$right];

            if ($sum === $target) {
                return [$sorted[$i], $sorted[$left], $sorted[$right]];
            } elseif ($sum < $target) {
                $left++;
            } else {
                $right--;
            }
        }
    }

    return false;
}

$arrayInput = $_POST['array'] ?? '12, 3, 4, 1, 6, 9';
$valueInput = $_POST['value'] ?? '24';
$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parts = array_filter(array_map('trim', explode(',', $arrayInput)), fn($v) => $v !== '');
    $arr = array_map('intval', $parts);
    $target = (int) $valueInput;

    if (empty($arr)) {
        $error = "Please enter a valid comma separated array.";
    } else {
        $triplet = findTripletSum($arr, $target);
        $result = $triplet === false ? false : $triplet;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Task 6 - Triplet Sum</title>
<link rel="stylesheet" href="styles.css"/>

</head>
<body>
<div class="wrap">
  <a class="back" href="/basiz/index.php">&larr; Back to assignments</a>
  <div class="card">
    <h1>Triplet Sum in Array</h1>
    <form method="POST">
      <label>Array (comma separated)</label>
      <input type="text" name="array" value="<?= htmlspecialchars($arrayInput) ?>" placeholder="12, 3, 4, 1, 6, 9">

      <label>Target Value</label>
      <input type="text" name="value" value="<?= htmlspecialchars($valueInput) ?>" placeholder="24">

      <button type="submit">Find Triplet</button>
    </form>
  </div>

  <?php if ($error): ?>
    <div class="card"><p class="error"><?= htmlspecialchars($error) ?></p></div>
  <?php elseif ($result !== null): ?>
    <div class="card">
      <h1>Result</h1>
      <?php if ($result === false): ?>
        <div class="not-found">false — No triplet found whose sum equals <?= htmlspecialchars($valueInput) ?>.</div>
      <?php else: ?>
        <div class="result">
          true — Triplet found: {<?= implode(', ', $result) ?>}<br>
          Sum = <?= array_sum($result) ?>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
