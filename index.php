<?php

    //get database cred and queries
    include '/home/sitrucp/config/db.php';

    //connect to database
    $conn = mysqli_connect(
        $hostname,
        $username,
        $password,
        $database);

    if (!$conn) {
        die();
    }

    // get query results
    $result_days = mysqli_query($conn, $query_days);
    $result_max = mysqli_query($conn, $query_max);
    $max_row = mysqli_fetch_array($result_max) or die(mysql_error());

    //create data table for Google Chart
    $table = array();
    //create table columns
    $table['cols'] = array(
        array('label' => 'days', 'type' => 'number'),
        array('label' => 'followers', 'type' => 'number')
    );
    //and table rows
    $rows = array();
    while($r = mysqli_fetch_assoc($result_days)) {
        $temp = array();
        //$temp[] = array('v' => $r['days']);
        $temp[] = array('v' => $r['days']);
        $temp[] = array('v' => $r['follower_count']);
        // insert the temp array into $rows
        $rows[] = array('c' => $temp);
    }

    // populate the table with rows of data
    $table['rows'] = $rows;

    // encode the table as JSON
    $jsonTable = json_encode($table);

    // Free result set and close conn
    mysqli_free_result($result_days);
    mysqli_free_result($result_max);
    mysqli_close($conn);

?>


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">

	google.load('visualization', '1', {'packages':['corechart']});

	google.setOnLoadCallback(drawChart);

	function drawChart() {

        var data = new google.visualization.DataTable(<?php echo $jsonTable ?>);

        var options = {
            hAxis: {
              title: 'Days since first Tweet',
              gridlines: {
                    color: 'transparent'
                }  
            },
            vAxis: {
              title: 'Follower Count',
              gridlines: {
                    color: 'transparent',
                },
              format: 'short'
            },
            legend: { position: 'none' },
            backgroundColor: 'none'
          };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
</script>

<body>

    <center>
    
        <p><strong>Cuba Gooding Jr @cubagoodingjr - follower count by day since first tweet (February 11, 2016)</strong></p>

        <?php
        echo '<p>Cuba currently has ' .number_format($max_row['max_count']). ' followers after ' .number_format($max_row['max_days']). ' days</p>';
        ?>
        
        <img src="https://pbs.twimg.com/profile_images/697529946617544704/OpXPfXwR.jpg" alt=" @cubagoodingjr" width="100px">
        
        <div id="chart_div" style="width: 70%; height: 300px;"></div>
        
    </center>
   
</body>


