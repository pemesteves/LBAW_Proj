@extends('layouts.uconnect_basic')

<?php
    function getUpdateDate($date){
        $datetime1 = new DateTime($date);
        $datetime2 = new DateTime();

        return date_diff($datetime1, $datetime2, true)->format("%a");
    }
?>

@section('content')
<div id="feed_container" class="container" >
    <div id="event_card" class="container card mb-3 border rounded">
        <div class="row">      
            <img 
            @if (isset($image) && $image !== null)
                src="{{$image->file_path}}"
            @else
                src=""
            @endif
            class="card-img-top mx-auto d-block" alt="event_image"/>
        </div>
        <div class="card row">
            <div class="row">
                <div class="col-sm-11">
                    <div class="card-body">
                        <h1 class="card-title uconnect-title" style='display:inline-block'>{{ $event->name }}</h1>
                            <button type="button" class="btn btn-light btn-link interaction_opt" 
                                style="float:right;margin-right:20px;background-color: rgba(0,0,150,.03); "
                                onclick="window.location.href='/events/{{$event->event_id}}'">
                                Back To Event Page
                            </button>
                        <p class="card-text uconnect-paragraph" >{{ $event->information }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer no-gutters">            
                <div class="row">
                    <div class="col-sm-6">
                        <span class="fa fa-calendar"></span> {{ date('d-m-Y, H:i', strtotime($event->date)) }}
                    </div>
                    <div class="col-sm-6">
                        <span class="fa fa-user"></span>&nbsp; {{ $going }} going
                    </div>
                </div>    
                <div class="row" >
                    <div class="col-sm-6">
                        <span class="fa fa-map-pin"></span>&nbsp;&nbsp; {{ $event->location }}
                    </div>
                    <div class="col-sm-6">
                        <span class="fa fa-history"></span>&nbsp; Updated <?= getUpdateDate($event->date); ?> days ago
                    </div>
                </div>   
            </div>
        </div>
    </div>
    <?php
 
?>
    <div class="col-sm-8" style="flex-grow:1;max-width:80%; background-color: whitesmoke; padding: 1em; margin: 0 auto;">
        <div class="stats">
            <h2>Day with more posts:</h2>
            <?php 
            try{
                $maxDay = array_keys($postsPerDay, max($postsPerDay))[0];?> 
                <p>{{$maxDay}} - {{$postsPerDay[$maxDay]}}
                    @if ($postsPerDay[$maxDay] === 1)
                        post
                    @else
                        posts
                    @endif
                </p>
            <?php
            }catch(ErrorException $e){
                echo '<p>There are no posts yet!</p>';
            }?>
        </div>
        <div class="stats">
            <h2>Day with fewer posts:</h2>
            <?php 
            try{
                $minDay = array_keys($postsPerDay, min($postsPerDay))[0];?>
                <p>{{$minDay}} - {{$postsPerDay[$minDay]}}
                    @if ($postsPerDay[$minDay] === 1)
                        post
                    @else
                        posts
                    @endif
                </p>
            <?php
            }catch(ErrorException $e){
                echo '<p>There are no posts yet!</p>';
            }?>
        </div>
        @if($firstDay)
        <div id="postsPerUserChart" class="graph" style="height: 370px; width: 100%;"></div>
        <div id="postsPerUserDayChart" class="graph" style="height: 370px; width: 100%;"></div>
        @endif
        <div id="usersPerDayChart" class="graph" style="height: 370px; width: 100%;"></div>
    </div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</div>
<script>
    window.onload = function () {
    <?php if($firstDay){?>
        let postsPerUserChart = new CanvasJS.Chart("postsPerUserChart", {
            animationEnabled: true,
            exportEnabled: true,
            title:{
                text: "Posts Per User Interested In Event"
            },
            subtitles: [],
            data: [{
                type: "pie",
                showInLegend: "true",
                legendText: "{label}",
                indexLabelFontSize: 16,
                indexLabel: "{label} - #percent%",
                yValueFormatString: "฿#,##0",
                dataPoints: <?php echo json_encode($posts_per_user, JSON_NUMERIC_CHECK); ?>
            }]
        });
        postsPerUserChart.render();

        const lastDay = new Date(<?php echo $lastDay;?>);
        const firstDay = new Date(<?php echo $firstDay;?>);
        let interval = 1;
        let intervalType = "year";
        let formatDate = "YYYY";
        let titleName = "Year";

        if(lastDay.getFullYear() === firstDay.getFullYear()){
            if(lastDay.getMonth() === firstDay.getMonth()){
                titleName = "Day";
                    formatDate = "DD/MM";
                if(lastDay.getDate() - firstDay.getDate() <= 2){
                    interval = 6;
                    intervalType = "hour";
                }else{
                    interval = 3;
                    intervalType = "day";
                }
            }else{
                titleName = "Month";
                intervalType = "month";
                formatDate = "MM/YYYY";
            }
        }

        let postsPerUserDayChart = new CanvasJS.Chart("postsPerUserDayChart", {
            animationEnabled: true,
            title:{
                text: "Number of Posts by " + titleName
            },
            axisY: {
                title: "Number of Posts"
            },
            axisX: {
                labelFormatter: function(e){
                    return CanvasJS.formatDate(e.value, formatDate);
                },
                interval: interval,
                intervalType: intervalType,
            },
            data: [{
                type: "spline",
                markerSize: 5,
                xValueType: "dateTime",
                dataPoints: <?php echo json_encode($postsPerDayOfYear, JSON_NUMERIC_CHECK); ?>
	        }]
        });

        postsPerUserDayChart.render();
    <?php } ?>

        const lastUserDay = new Date(<?php echo $lastUserDay;?>);
        const firstUserDay = new Date(<?php echo $firstUserDay;?>);
        interval = 1;
        intervalType = "year";
        formatDate = "YYYY";
        if(lastUserDay.getFullYear() === firstUserDay.getFullYear()){
            if(lastUserDay.getMonth() === firstUserDay.getMonth()){
                formatDate = "DD/MM";
                if(lastUserDay.getDate() - firstUserDay.getDate() <= 2){
                    interval = 6;
                    intervalType = "hour";
                }else{
                    interval = 3;
                    intervalType = "day";
                }
            }else{
                intervalType = "month";
                formatDate = "MM/YYYY";
            }
        }

        let usersPerDayChart = new CanvasJS.Chart("usersPerDayChart", {
            animationEnabled: true,
            title:{
                text: "Number of Users Interested In This Event"
            },
            axisY: {
                title: "Number of Users"
            },
            axisX: {
                labelFormatter: function(e){
                    return CanvasJS.formatDate(e.value, formatDate);
                },
                interval: interval,
                intervalType: intervalType,
            },
            data: [{
                type: "spline",
                markerSize: 5,
                xValueType: "dateTime",
                dataPoints: <?php echo json_encode($usersPerDay, JSON_NUMERIC_CHECK); ?>
	        }]
        });

        usersPerDayChart.render();
    }
</script>
@endsection