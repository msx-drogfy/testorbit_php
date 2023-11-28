<!DOCTYPE html>
<html>
<head>
    <?php
    function cipherShiftEncrypt($key, $message) {
        $shiftValues = array();
        for ($i = 0; $i < strlen($key); $i++) {
            array_push($shiftValues, ord(strtolower($key[$i])) - ord('a'));
        }
    
        $encryptedMessage = "";
        $keyLength = count($shiftValues);
    
        for ($i = 0; $i < strlen($message); $i++) {
            $char = $message[$i];
            if (ctype_alpha($char)) {
                $shift = $shiftValues[$i % $keyLength];
    
                if (ctype_lower($char)) {
                    $shiftedChar = chr((ord($char) - ord('a') + $shift) % 26 + ord('a'));
                } else {
                    $shiftedChar = chr((ord($char) - ord('A') + $shift) % 26 + ord('A'));
                }
    
                $encryptedMessage .= $shiftedChar;
            } else {
                $encryptedMessage .= $char;
            }
        }
    
        return $encryptedMessage;
    }
    
    function cipherShiftDecrypt($inputStr) {
        list($key, $encryptedMessage) = explode("::", $inputStr);
    
        $shiftValues = array();
        for ($i = 0; $i < strlen($key); $i++) {
            array_push($shiftValues, ord(strtolower($key[$i])) - ord('a'));
        }
    
        $decryptedMessage = "";
        $keyLength = count($shiftValues);
    
        for ($i = 0; $i < strlen($encryptedMessage); $i++) {
            $char = $encryptedMessage[$i];
            if (ctype_alpha($char)) {
                $shift = $shiftValues[$i % $keyLength];
    
                if (ctype_lower($char)) {
                    $shiftedChar = chr((ord($char) - ord('a') - $shift + 26) % 26 + ord('a'));
                } else {
                    $shiftedChar = chr((ord($char) - ord('A') - $shift + 26) % 26 + ord('A'));
                }
    
                $decryptedMessage .= $shiftedChar;
            } else {
                $decryptedMessage .= $char;
            }
        }
    
        return $decryptedMessage;
    }
    
    // Example Usage
    $inputStr = "KEY::Rijvs Gspvh";
    echo cipherShiftDecrypt($inputStr);
    
    
    ?>
    <title>Encryption/Decryption Tool</title>
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            padding: 15px;
            margin: 10px;
        }
        .container {
            padding: 2px 16px;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="container">
        <h4><b>Encrypt Message</b></h4>
        <form method="post">
            Key: <input type="text" name="encryptKey" required>
            Message: <input type="text" name="encryptMessage" required>
            <input type="submit" name="encryptSubmit" value="Encrypt">
        </form>
        <?php
        if (isset($_POST['encryptSubmit'])) {
            echo '<p>Encrypted Message: ' . cipherShiftEncrypt($_POST['encryptKey'], $_POST['encryptMessage']) . '</p>';
        }
        ?>
    </div>
</div>

<div class="card">
    <div class="container">
        <h4><b>Decrypt Message</b></h4>
        <form method="post">
            Input: <input type="text" name="decryptInput" placeholder="key::message" required>
            <input type="submit" name="decryptSubmit" value="Decrypt">
        </form>
        <?php
        if (isset($_POST['decryptSubmit'])) {
            echo '<p>Decrypted Message: ' . cipherShiftDecrypt($_POST['decryptInput']) . '</p>';
        }
        ?>
    </div>
</div>

</body>
</html>
