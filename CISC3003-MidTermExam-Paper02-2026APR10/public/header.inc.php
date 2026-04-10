<head>
    <title>DC226572 Wong Pou I- CISC3003 Suggested Exercise 10</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- USE LOCAL FILES instead of CDN to avoid 403 errors -->
    <link rel="stylesheet" href="css/material.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/demo-styles.css">
    
    <script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
    <!-- USE LOCAL FILE instead of CDN -->
    <script src="js/material.min.js"></script>
    <script src="js/jquery.sparkline.2.1.2.js"></script>
    
    <script type="text/javascript">
        // Initialize sparklines after document is ready
        $(document).ready(function() {
            $('.inlinesparkline').each(function() {
                var $this = $(this);
                var values = $this.text().split(',');
                // Clean up any whitespace in values
                var cleanValues = [];
                for (var i = 0; i < values.length; i++) {
                    var val = $.trim(values[i]);
                    if (val !== '') {
                        cleanValues.push(parseInt(val) || 0);
                    }
                }
                $this.sparkline(cleanValues, {
                    type: 'bar',
                    barColor: '#ff5722',
                    height: '30px',
                    barWidth: 5,
                    barSpacing: 2
                });
            });
        });
    </script>
</head>