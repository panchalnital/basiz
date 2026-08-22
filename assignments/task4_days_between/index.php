<?php

/**
 * Converts an integer into its English words representation.
 */
function numberToWords(int $number): string {
    if ($number === 0) return "zero";

    $ones = ["", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine",
             "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen",
             "seventeen", "eighteen", "nineteen"];
    $tens = ["", "", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"];

    $words = "";

    if ($number < 0) {
        $words .= "negative ";
        $number = abs($number);
    }

    $groups = [
        [1000000000, "billion"],
        [1000000, "million"],
        [1000, "thousand"],
        [100, "hundred"],
    ];

    $parts = [];

    foreach ($groups as [$value, $label]) {
        if ($number >= $value) {
            $count = intdiv($number, $value);
            $parts[] = numberToWords($count) . " " . $label;
            $number %= $value;
        }
    }

    if ($number > 0) {
        if ($number < 20) {
            $parts[] = $ones[$number];
        } else {
            $t = $tens[intdiv($number, 10)];
            $o = $number % 10;
            $parts[] = $o > 0 ? $t . "-" . $ones[$o] : $t;
        }
    }

    return $words . implode(" ", $parts);
}

$result = null;
$error = null;
$date1 = $_POST['date1'] ?? '26/12/2022';
$date2 = $_POST['date2'] ?? '29/12/2024';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d1 = DateTime::createFromFormat('d/m/Y', $date1);
    $d2 = DateTime::createFromFormat('d/m/Y', $date2);

    if (!$d1 || !$d2) {
        $error = "Please enter valid dates in dd/mm/yyyy format.";
    } else {
        $diff = $d1->diff($d2);
        $days = (int) $diff->days;
        $wordsStr = ucfirst(numberToWords($days));
        $result = [
            'days' => $days,
            'words' => $wordsStr . " Days",
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Task 4 - Days Between Two Dates</title>
<link rel="stylesheet" href="styles.css"/>

</head>
<body>
<div class="wrap">
  <a class="back" href="/basiz/index.php">&larr; Back to assignments</a>
  <div class="card">
    <h1>Days Between Two Dates</h1>
    <form method="POST">
      <label>Date 1 (dd/mm/yyyy)</label>
      <input type="text" name="date1" value="<?= htmlspecialchars($date1) ?>" placeholder="26/12/2022">

      <label>Date 2 (dd/mm/yyyy)</label>
      <input type="text" name="date2" value="<?= htmlspecialchars($date2) ?>" placeholder="29/12/2024">

      <button type="submit">Calculate</button>
    </form>
  </div>

  <?php if ($error): ?>
    <div class="card"><p class="error"><?= htmlspecialchars($error) ?></p></div>
  <?php elseif ($result): ?>
    <div class="card">
      <h1>Result</h1>
      <div class="result">
        <p><?= htmlspecialchars((string)$result['days']) ?> Days</p>
        <p><?= htmlspecialchars($result['words']) ?></p>
      </div>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
