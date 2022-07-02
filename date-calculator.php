<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date Calculator</title>
    <link rel="stylesheet" href="date-calculator.css">
</head>
<body>
    <?php
    $checked_diff = false;
    $check_month = false;
    $check_hour = false;

    $start_date = "";
    $end_date = "";

    $answer = "";
    if ( $_SERVER["REQUEST_METHOD"] === "POST" ) {
        if ( isset( $_POST["start_date"] ) && $_POST["start_date"] ) {
            $start_date = clean_input( $_POST["start_date"] );
        }
        if ( isset( $_POST["end_date"] ) && $_POST["end_date"] ) {
            $end_date = clean_input( $_POST["end_date"] );
        }
        if( $start_date && $end_date ){
            if( isset( $_POST['date_action'] ) ) {
                $start = new DateTime( $start_date );
                $end = new DateTime( $end_date );
                $interval = $start->diff( $end );
                if( $_POST['date_action'] === "difference" ){
                    $checked_diff = true;
                    $answer = maybe_plural( $interval->days, "day", "days" );
                } else if ( $_POST['date_action'] === "month" ){
                    $check_month = true;
                    $answer = maybe_plural( $interval->m + ( 12 * $interval->y ), "month", "months" );
                } else if ( $_POST['date_action'] === "hour" ){
                    $check_hour = true;
                    $answer = maybe_plural( ( $interval->days * 24 ) + $interval->h, "hour", "hours" );
                }
            }
        }
    }
    // Pluralize words or not
    function maybe_plural( $amount, $singular, $plural, $custom = 0 ) {
        if ( $amount == 1 ) {
            return "$amount $singular";
        } else if ( ! $amount ) {
            return "$custom $plural";
        }
        return "$amount $plural";
    }
    function clean_input( $data ) {
        $data = stripslashes( trim( $data ) );
        $data = htmlspecialchars( $data );
        return $data;
    }
    ?>
    <div class="date-container">
        <div class="calculator">
            <form method="post" action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"] ); ?>">
                <div class="date-field">
                    <div class="field">
                        <label for="start-date">Start Date</label>
                        <input type="date" id="start-date" name="start_date" value="<?php echo $start_date; ?>" min="" max="">
                        <div class="error<?php echo ( isset( $_POST["start_date"] ) && ! $start_date ) ? " show" : ""; ?>">Start date is required</div>
                    </div>
                    <div class="field">
                        <label for="end-date">End Date</label>
                        <input type="date" id="end-date" name="end_date" value="<?php echo $end_date; ?>" min="" max="">
                        <div class="error<?php echo ( isset( $_POST["end_date"] ) && ! $end_date ) ? " show" : ""; ?>">End date is required</div>
                    </div>
                </div>
                <div class="date-actions">
                    <div class="action">
                        <input type="radio" id="difference" name="date_action" value="difference" <?php echo $checked_diff ? "checked" : ""; ?>>
                        <label for="difference"> Day difference</label>
                    </div>
                    <div class="action">
                        <input type="radio" id="month" name="date_action" value="month" <?php echo $check_month ? "checked" : ""; ?>>
                        <label for="month"> Month difference</label><br>
                    </div>
                    <div class="action">
                        <input type="radio" id="hour" name="date_action" value="hour" <?php echo $check_hour ? "checked" : ""; ?>>
                        <label for="hour"> Hour difference</label><br>
                    </div>
                </div>
                <div class="error<?php echo ( ( $start_date && $end_date ) && ! isset( $_POST['date_action'] ) ) ? " show" : ""; ?>">Please choose an action</div>
                <button type="submit">Submit</button>
            </form>
            <div class="answer"><?php echo "Answer: $answer" ?></div>
        </div>
    </div>
</body>
</html>