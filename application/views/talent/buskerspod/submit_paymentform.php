<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($detail) && isset($amount) && isset($order_id) && isset($name) && isset($email) && isset($phone)){
    # assuming all of the data passed is correct and no validation required. Preferably you will need to validate the data passed
    $hashed_string = md5($secretkey.urldecode($detail).urldecode($amount).urldecode($order_id));
    # now we send the data to senangPay by using post method
    ?>
    <html>
        <head>
            <title>senangPay Form</title>
        </head>
        <body onload="document.order.submit()">
            <form name="order" method="post" action="https://app.senangpay.my/payment/<?php echo $merchant_id; ?>">
                <input type="hidden" name="detail" value="<?php echo $detail; ?>">
                <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                <input type="hidden" name="name" value="<?php echo $name; ?>">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <input type="hidden" name="phone" value="<?php echo $phone; ?>">
                <input type="hidden" name="hash" value="<?php echo $hashed_string; ?>">
            </form>
        </body>
        <script type="text/javascript">
            sessionStorage.setItem("counter", '');
        </script>
    </html>
    <?php
}
?>