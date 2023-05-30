<!DOCTYPE html>
<html lang="en">

<head>
    <!-- for html to pdf cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
</head>

<body>
    <?
    if (isset($_POST["htmlContent"])) {
        echo '<div style="-webkit-transform: rotate(90deg);" id="htmlContent"';
        echo $_POST["htmlContent"];
        echo '</div>';
    }
    ?>

</body>
<script>generatePDF();</script>

</html>