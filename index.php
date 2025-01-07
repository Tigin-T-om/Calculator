<?php
// Initialize variables for server-side calculation
$result = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expression = $_POST['expression'] ?? '';
    try {
        // Use eval for basic arithmetic (not recommended for complex or untrusted input)
        if (!empty($expression)) {
            $result = eval("return $expression;");
        } else {
            $result = '0'; // Default to 0 if expression is empty
        }
    } catch (Throwable $e) {
        $result = 'Error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="calculator">
        <form method="POST">
            <div class="display" id="display">
                <?php echo htmlspecialchars($result ?? ''); // Ensure a valid string ?>
            </div>
            <input type="hidden" name="expression" id="expression">
            <div class="buttons">
                <button type="button" class="btn" onclick="appendValue('1')">1</button>
                <button type="button" class="btn" onclick="appendValue('2')">2</button>
                <button type="button" class="btn" onclick="appendValue('3')">3</button>
                <button type="button" class="btn" onclick="appendValue('+')">+</button>

                <button type="button" class="btn" onclick="appendValue('4')">4</button>
                <button type="button" class="btn" onclick="appendValue('5')">5</button>
                <button type="button" class="btn" onclick="appendValue('6')">6</button>
                <button type="button" class="btn" onclick="appendValue('-')">-</button>

                <button type="button" class="btn" onclick="appendValue('7')">7</button>
                <button type="button" class="btn" onclick="appendValue('8')">8</button>
                <button type="button" class="btn" onclick="appendValue('9')">9</button>
                <button type="button" class="btn" onclick="appendValue('*')">*</button>

                <button type="button" class="btn" onclick="appendValue('0')">0</button>
                <button type="button" class="btn" onclick="clearDisplay()">C</button>
                <button type="submit" class="btn">=</button>
                <button type="button" class="btn" onclick="appendValue('/')">/</button>
            </div>
        </form>
    </div>

    <script>
        let expressionField = document.getElementById('expression');
        let display = document.getElementById('display');
        let currentValue = '';
        let hasEvaluated = false;

        // Function to append values
        function appendValue(value) {
            if (hasEvaluated && !isOperator(value)) {
                // Clear the expression if the last action was evaluation and a number is pressed
                currentValue = '';
            }
            hasEvaluated = false;

            currentValue += value;
            expressionField.value = currentValue;
            display.innerText = currentValue;
        }

        // Function to clear the display
        function clearDisplay() {
            currentValue = '';
            expressionField.value = currentValue;
            display.innerText = '';
        }

        // Function to check if a character is an operator
        function isOperator(char) {
            return ['+', '-', '*', '/'].includes(char);
        }

        // Handle form submission to trigger PHP calculation
        document.querySelector('form').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent form submission which causes page refresh

            // Set the current expression to the display field (which is hidden)
            expressionField.value = currentValue;
            hasEvaluated = true; // Mark the result as evaluated
            this.submit(); // Manually submit the form after setting the expression
        });
    </script>
</body>
</html>
