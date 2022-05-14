<?php
$telemetry_extra = array();
$telemetry_extra[0] = $_GET['token'];
$telemetry_extra[1] = abs( crc32( uniqid() ) );
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no" />
<meta charset="UTF-8" />
<link rel="shortcut icon" href="favicon.png">

    <style type="text/css">
        @font-face {
            font-family: 'iranSans';
            src: url("./fonts/iranSans.eot");
            /* IE9 Compat Modes */
            src: url("./fonts/iranSans.eot?#iefix") format("embedded-opentype"), url("./fonts/iranSans.ttf") format("truetype");
            /* Legacy iOS */
            /*unicode-range: U+007F UFFFF;*/
        }

        @font-face {
            font-family: 'Yekan';
            src: url("./fonts/Yekan.eot");
            /* IE9 Compat Modes */
            src: url("./fonts/Yekan.eot?#iefix") format("embedded-opentype"), url("./fonts/Yekan.woff") format("woff"), url("./fonts/Yekan.ttf") format("truetype"), url("./fonts/Yekan.svg#svgFontName") format("svg");
            /* Legacy iOS */
        }

        html, body {
            border: none;
            padding: 0;
            margin: 0;
            background: #FFFFFF;
            color: #202020;
        }

        body {
            font-family: iranSans, Yekan;
            text-align: center;
            font-size: 16px;
        }

        h1 {
            color: #404040;
        }

        #startStopBtn {
            display: inline-block;
            margin: 0 auto;
            color: #6060AA;
            background-color: rgba(0, 0, 0, 0);
            border: 0.15em solid #6060FF;
            border-radius: 0.3em;
            transition: all 0.3s;
            box-sizing: border-box;
            width: 8em;
            height: 3em;
            line-height: 2.7em;
            cursor: pointer;
            box-shadow: 0 0 0 rgba(0, 0, 0, 0.1), inset 0 0 0 rgba(0, 0, 0, 0.1);
        }

        #startStopBtn:hover {
            box-shadow: 0 0 2em rgba(0, 0, 0, 0.1), inset 0 0 1em rgba(0, 0, 0, 0.1);
        }

        #startStopBtn.running {
            background-color: #FF3030;
            border-color: #FF6060;
            color: #FFFFFF;
        }

        #startStopBtn:before {
            content: "شروع تست";
        }

        #startStopBtn.running:before {
            content: "لغو";
        }

        #test {
            margin-top: 2em;
            margin-bottom: 3em;
        }

        div.testArea {
            display: inline-block;
            width: 16em;
            height: 12.5em;
            position: relative;
            box-sizing: border-box;
        }

        div.testName {
            position: absolute;
            top: 0.1em;
            left: 0;
            width: 100%;
            font-size: 1.4em;
            z-index: 9;
        }

        div.meterText {
            position: absolute;
            bottom: 1.55em;
            left: 0;
            width: 100%;
            font-size: 2.5em;
            z-index: 9;
        }

        div.meterText:empty:before {
            content: "0.00";
        }

        div.unit {
            position: absolute;
            bottom: 2em;
            left: 0;
            width: 100%;
            z-index: 9;
        }

        div.testArea canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        div.testGroup {
            display: inline-block;
        }

        @media all and (max-width: 65em) {
            body {
                font-size: 1.5vw;
            }
        }

        @media all and (max-width: 40em) {
            body {
                font-size: 0.8em;
            }

            div.testGroup {
                display: block;
                margin: 0 auto;
            }
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            font-size: 14px;
            direction: rtl;
            max-width: 60%;
            margin-left: auto;
            margin-right: auto;
        }

        .alert-danger {
            color: #b94a48;
            background-color: #f2dede;
            border-color: #eed3d7;
        }

        .alert-success {
            color: #468847;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }

        .report {
        }

        .report a {
            text-decoration: none;
            /*border-color: #2196F3;*/
            color: dodgerblue;
            /*border: 1px solid ;*/
            background-color: white;
            padding: 6px 14px;
            font-size: 16px;
            cursor: pointer;
            /*border-radius: 0.3em;*/
        }

        .report a:hover {
            background: #2196F3;
            color: white;
        }


        .box {
            float: left;
            width: 33.33%;
            padding: 50px;
            box-sizing: border-box;
        }

        .clearfix{
            width: 80%;
            margin-right: auto;
            margin-left: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
            box-sizing: border-box;
        }

    </style>
	
    <script type="text/javascript">
        function I(id) {
            return document.getElementById(id);
        }

        var meterBk = "#E0E0E0";
        var dlColor = "#6060AA",
            ulColor = "#309030",
            pingColor = "#AA6060",
            jitColor = "#AA6060";
        var progColor = "#EEEEEE";

        //CODE FOR GAUGES
        function drawMeter(c, amount, bk, fg, progress, prog) {
            var ctx = c.getContext("2d");
            var dp = window.devicePixelRatio || 1;
            var cw = c.clientWidth * dp, ch = c.clientHeight * dp;
            var sizScale = ch * 0.0055;
            if (c.width == cw && c.height == ch) {
                ctx.clearRect(0, 0, cw, ch);
            } else {
                c.width = cw;
                c.height = ch;
            }
            ctx.beginPath();
            ctx.strokeStyle = bk;
            ctx.lineWidth = 16 * sizScale;
            ctx.arc(c.width / 2, c.height - 58 * sizScale, c.height / 1.8 - ctx.lineWidth, -Math.PI * 1.1, Math.PI * 0.1);
            ctx.stroke();
            ctx.beginPath();
            ctx.strokeStyle = fg;
            ctx.lineWidth = 16 * sizScale;
            ctx.arc(c.width / 2, c.height - 58 * sizScale, c.height / 1.8 - ctx.lineWidth, -Math.PI * 1.1, amount * Math.PI * 1.2 - Math.PI * 1.1);
            ctx.stroke();
            if (typeof progress !== "undefined") {
                ctx.fillStyle = prog;
                ctx.fillRect(c.width * 0.3, c.height - 16 * sizScale, c.width * 0.4 * progress, 4 * sizScale);
            }
        }

        function mbpsToAmount(s) {
            return 1 - (1 / (Math.pow(1.3, Math.sqrt(s))));
        }

        function msToAmount(s) {
            return 1 - (1 / (Math.pow(1.08, Math.sqrt(s))));
        }

        //SPEEDTEST AND UI CODE
		//var testId =  Math.floor(Math.random() * (999999 - 100000 + 1)) + 100000;
        
		
		var w = null; //speedtest worker
        var data = null; //data from worker
        function startStop(telemetry_extra = '') {
            if (w != null) {
                //speedtest is running, abort
                w.postMessage('abort');
                w = null;
                data = null;
                I("startStopBtn").className = "";
                I("ref_num").style.display = 'none';
                initUI();
            } else {
                //test is not running, begin
                w = new Worker('speedtest_worker.min.js');
				//Add optional parameters as a JSON object to this command
				<?php
				$str = 'start {"telemetry_level":"basic", "getIp_ispInfo_distance": "false", "url_telemetry":"telemetry.php?ref_test='.$telemetry_extra[1].'","telemetry_extra":"'.$telemetry_extra[0].'"}';
				?>
                w.postMessage(<?php echo "'".$str."'"; ?>); 
                I("startStopBtn").className = "running";
                I("ref_num").style.display = 'none';
                w.onmessage = function (e) {
                    data = JSON.parse(e.data);
                    var status = data.testState;
					
                    if (status >= 4) {
                        //test completed
                        I("startStopBtn").className = "";
                        w = null;
                        updateUI(true);

                        console.log(telemetry_extra);
						I("ref_num").style.display = 'block';
                        I("ref_num").getElementsByClassName("result")[0].innerHTML = <?php echo $telemetry_extra[1]; ?>;
						
                        if (data.testId != 'noID') {
                            I("ref_num").style.display = 'block';
                            I("ref_num").getElementsByClassName("result")[0].innerHTML = data.testId;
                        }

                    }
                };
            }
        }

        //this function reads the data sent back by the worker and updates the UI
        function updateUI(forced) {
            if (!forced && (!data || !w)) return;
            var status = data.testState;
            I("ip").textContent = data.clientIp;
            I("dlText").textContent = (status == 1 && data.dlStatus == 0) ? "..." : data.dlStatus;
            drawMeter(I("dlMeter"), mbpsToAmount(Number(data.dlStatus * (status == 1 ? oscillate() : 1))), meterBk, dlColor, Number(data.dlProgress), progColor);
            I("ulText").textContent = (status == 3 && data.ulStatus == 0) ? "..." : data.ulStatus;
            drawMeter(I("ulMeter"), mbpsToAmount(Number(data.ulStatus * (status == 3 ? oscillate() : 1))), meterBk, ulColor, Number(data.ulProgress), progColor);
            I("pingText").textContent = data.pingStatus;
            drawMeter(I("pingMeter"), msToAmount(Number(data.pingStatus * (status == 2 ? oscillate() : 1))), meterBk, pingColor, Number(data.pingProgress), progColor);
            I("jitText").textContent = data.jitterStatus;
            drawMeter(I("jitMeter"), msToAmount(Number(data.jitterStatus * (status == 2 ? oscillate() : 1))), meterBk, jitColor, Number(data.pingProgress), progColor);
        }

        function oscillate() {
            return 1 + 0.02 * Math.sin(Date.now() / 100);
        }

        //poll the status from the worker (this will call updateUI)
        setInterval(function () {
            if (w) w.postMessage('status');
        }, 200);
        //update the UI every frame
        window.requestAnimationFrame = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.msRequestAnimationFrame || (function (callback, element) {
            setTimeout(callback, 1000 / 60);
        });

        function frame() {
            requestAnimationFrame(frame);
            updateUI();
        }

        frame(); //start frame loop
        //function to (re)initialize UI
        function initUI() {
            drawMeter(I("dlMeter"), 0, meterBk, dlColor, 0);
            drawMeter(I("ulMeter"), 0, meterBk, ulColor, 0);
            drawMeter(I("pingMeter"), 0, meterBk, pingColor, 0);
            drawMeter(I("jitMeter"), 0, meterBk, jitColor, 0);
            I("dlText").textContent = "";
            I("ulText").textContent = "";
            I("pingText").textContent = "";
            I("jitText").textContent = "";
            I("ip").textContent = "";
        }

    </script>
    <title>سامانه سنجش سرعت و کیفیت شرکت فناپ تلکام</title>
</head>
<body>
<img src="Fanaptelecom_logo.png" style="width: 156px; height: 176px;"/>
<h1> سامانه سنجش سرعت و کیفیت لینک اینترانت </h1>
<div id="test">
    <div class="testGroup">
        <div class="testArea">
            <div class="testName">Download</div>
            <canvas id="dlMeter" class="meter"></canvas>
            <div id="dlText" class="meterText"></div>
            <div class="unit">Mbps</div>
        </div>
        <div class="testArea">
            <div class="testName">Upload</div>
            <canvas id="ulMeter" class="meter"></canvas>
            <div id="ulText" class="meterText"></div>
            <div class="unit">Mbps</div>
        </div>
    </div>
    <div class="testGroup">
        <div class="testArea">
            <div class="testName">Ping</div>
            <canvas id="pingMeter" class="meter"></canvas>
            <div id="pingText" class="meterText"></div>
            <div class="unit">ms</div>
        </div>
        <div class="testArea">
            <div class="testName">Jitter</div>
            <canvas id="jitMeter" class="meter"></canvas>
            <div id="jitText" class="meterText"></div>
            <div class="unit">ms</div>
        </div>
    </div>
    <div id="ipArea">
        IP Address: <span id="ip"></span>
    </div>
	
	<div id="ref_num" style="display: none" class="alert alert-success">
        <p>
            شماره پیگیری :
             <strong class="result"></strong>
        </p>
    </div>
	
			<div id="startStopBtn" onclick="startStop('<?= $telemetry_extra ?>')"></div>
	
</div>




	
	
<script type="text/javascript">setTimeout(function(){initUI()},100);</script>
</body>
</html>
