<?php
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

$userIP = getUserIP();
include 'includes/version.php';
// Fetch IP details
$details = json_decode(file_get_contents("http://ipapi.co/{$userIP}/json/"), true);

// VPN/Proxy Detection and IP Reputation Check (using IPHub)
$ipHubApiKey = 'Enter_Your_API_KEY'; // Replace with your actual IPHub API key
$ipHubUrl = "http://v2.api.iphub.info/ip/{$userIP}";
$ipHubHeaders = ["X-Key: {$ipHubApiKey}"];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ipHubUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $ipHubHeaders);
$ipHubResponse = curl_exec($ch);
curl_close($ch);

$ipHubData = json_decode($ipHubResponse, true);

// Weather Information (using OpenWeatherMap)
$weatherApiKey = 'Enter_Your_API_KEY'; // Replace with your actual OpenWeatherMap API key
$lat = $details['latitude'];
$lon = $details['longitude'];
$weatherData = json_decode(file_get_contents("https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$weatherApiKey}&units=metric"), true);


// IP Address Type Detector
function getIPType($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) 
           ? 'IPv4' 
           : (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 'IPv6' : 'Invalid IP');
}

$ipType = getIPType($userIP);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Insight - Academic Resource Portal  </title>
        <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        /* body {
            font-family: 'Roboto', sans-serif;
            /* background: linear-gradient(45deg, #1a1a2e, #16213e); 
background: #fff;
            color: #000;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        } */
        .ip-container {
            max-width: 1000px;
            margin: 0 auto;
            /* background: rgba(255, 255, 255, 0.1); */
            border-radius: 10px;
            backdrop-filter: blur(10px);
            padding: 2rem;
        }
        h1, h2 {
            text-align: center;
        }
        .ip-display {
            font-size: 2.5rem;
            font-weight: bold;
            color: #0010ff;
            text-align: center;
            margin-bottom: 1rem;
        }
        .ip-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .ip-detail-item {
            background:rgba(205, 208, 219, 0.18);
            padding: 1rem;
            border-radius: 5px;
        }
        .ip-detail-item strong {
            color:#493ca6;
        }
        #map {
            height: 300px;
            margin-top: 2rem;
            border-radius: 5px;
        }
        .ip-button {
            background-color: #0010ff;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 5px;
            margin-top: 1rem;
            width: 100%;
        }
        .ip-button:hover {
            background-color: #656cc8;
        }
#pingTest {
            margin-top: 2rem;
        }
.feature-section {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
}

.feature-section h2 {
    color: #000;
    margin-top: 0;
}

.feature-section p, .feature-section ul {
    margin: 10px 0;
}

.feature-section ul {
    padding-left: 20px;
}

    </style>
</head>
<body>
    <header>
     <?php include 'includes/header.php'; ?>
    </header>

    <div class="ip-container">
        <h1>Detailed IP Address Lookup and Analysis</h1>
        <div class="ip-display"><?php echo $userIP; ?></div>

        <h2>IP Details</h2>
        <div class="ip-details">
            <div class="ip-detail-item"><strong>ISP:</strong> <?php echo $details['org'] ?? 'N/A'; ?></div>
            <div class="ip-detail-item"><strong>IP Type:</strong> <?php echo htmlspecialchars($ipType); ?></div>
            <div class="ip-detail-item"><strong>Country:</strong> <?php echo $details['country_name'] ?? 'N/A'; ?></div>
            <div class="ip-detail-item"><strong>City:</strong> <?php echo $details['city'] ?? 'N/A'; ?></div>
            <div class="ip-detail-item"><strong>Region:</strong> <?php echo $details['region'] ?? 'N/A'; ?></div>
            <div class="ip-detail-item"><strong>Latitude:</strong> <?php echo $details['latitude'] ?? 'N/A'; ?></div>
            <div class="ip-detail-item"><strong>Longitude:</strong> <?php echo $details['longitude'] ?? 'N/A'; ?></div>
            <div class="ip-detail-item"><strong>Timezone:</strong> <?php echo $details['timezone'] ?? 'N/A'; ?></div>
        </div>

        <h2>VPN/Proxy Detection</h2>
        <div class="ip-details">
            <div class="ip-detail-item"><strong>Is VPN/Proxy:</strong> <?php echo $ipHubData['block'] == 1 ? 'Yes' : 'No'; ?></div>
            <div class="ip-detail-item"><strong>IP Type:</strong> <?php echo $ipHubData['isp'] ?? 'N/A'; ?></div>
        </div>

        <h2>IP Reputation Check</h2>
        <div class="ip-details">
            <div class="ip-detail-item"><strong>Reputation Score:</strong> <?php echo $ipHubData['block']; ?> (0 = Good, 1 = Bad, 2 = Unknown)</div>
        </div>

 <h2>Map</h2>
        <div id="map"></div>

        <h2>Weather Information</h2>
        <div class="ip-details">
            <div class="ip-detail-item"><strong>Temperature:</strong> <?php echo $weatherData['main']['temp'] ?? 'N/A'; ?>°C</div>
            <div class="ip-detail-item"><strong>Conditions:</strong> <?php echo $weatherData['weather'][0]['description'] ?? 'N/A'; ?></div>
        </div>

       
        <h2>Network Information</h2>
        <div id="networkInfo"></div>

        <h2>Browser and Device Information</h2>
        <div class="ip-detail-item" id="browserInfo"><strong></strong> </div>

        <div id="pingTest">
            <h2>Ping Test</h2>
            <button class="ip-button" onclick="pingTest()">Run Ping Test</button>
            <div class="ip-detail-item" id="pingResult"><strong></strong> </div>
        </div>
<!--features list -->

    <h2>Screen Information</h2>
            <div class="ip-detail-item" id="screenInfo"><strong></strong> </div>


    <h2>Video Capabilities</h2>
<div class="ip-detail-item" id="videoCapabilities"><strong></strong> </div>

    </div>
        <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
    <script>
        // Initialize the map
        var map = L.map('map').setView([<?php echo $details['latitude']; ?>, <?php echo $details['longitude']; ?>], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        L.marker([<?php echo $details['latitude']; ?>, <?php echo $details['longitude']; ?>]).addTo(map)
            .bindPopup('Your approximate location')
            .openPopup();

        // Browser and Device Information
        function getBrowserInfo() {
            var nVer = navigator.appVersion;
            var nAgt = navigator.userAgent;
            var browserName  = navigator.appName;
            var fullVersion  = ''+parseFloat(navigator.appVersion); 
            var majorVersion = parseInt(navigator.appVersion,10);
            var nameOffset,verOffset,ix;

            // In Opera, the true version is after "Opera" or after "Version"
            if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
                browserName = "Opera";
                fullVersion = nAgt.substring(verOffset+6);
                if ((verOffset=nAgt.indexOf("Version"))!=-1) 
                    fullVersion = nAgt.substring(verOffset+8);
            }
            // In MSIE, the true version is after "MSIE" in userAgent
            else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
                browserName = "Microsoft Internet Explorer";
                fullVersion = nAgt.substring(verOffset+5);
            }
            // In Chrome, the true version is after "Chrome" 
            else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
                browserName = "Chrome";
                fullVersion = nAgt.substring(verOffset+7);
            }
            // In Safari, the true version is after "Safari" or after "Version" 
            else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
                browserName = "Safari";
                fullVersion = nAgt.substring(verOffset+7);
                if ((verOffset=nAgt.indexOf("Version"))!=-1) 
                    fullVersion = nAgt.substring(verOffset+8);
            }
            // In Firefox, the true version is after "Firefox" 
            else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
                browserName = "Firefox";
                fullVersion = nAgt.substring(verOffset+8);
            }

            // trim the fullVersion string at semicolon/space if present
            if ((ix=fullVersion.indexOf(";"))!=-1)
                fullVersion=fullVersion.substring(0,ix);
            if ((ix=fullVersion.indexOf(" "))!=-1)
                fullVersion=fullVersion.substring(0,ix);

            majorVersion = parseInt(''+fullVersion,10);
            if (isNaN(majorVersion)) {
                fullVersion  = ''+parseFloat(navigator.appVersion); 
                majorVersion = parseInt(navigator.appVersion,10);
            }

            return browserName + ' ' + fullVersion;
        }

        document.getElementById('browserInfo').innerHTML = `
            <strong>Browser:</strong> ${getBrowserInfo()}<br>
            <strong>OS:</strong> ${navigator.platform}<br>
            <strong>User Agent:</strong> ${navigator.userAgent}
        `;

        // Network Information
        if ('connection' in navigator) {
            document.getElementById('networkInfo').innerHTML = `
                <strong>Connection Type:</strong> ${navigator.connection.effectiveType}<br>
                <strong>Downlink:</strong> ${navigator.connection.downlink} Mbps
            `;
        } else {
        networkInfo.innerHTML = 'Detailed Network Information not available in this browser.<br>' +
                                'Try using a Chromium-based browser like Chrome or Edge for this feature.';
    }

        // Ping Test
        function pingTest() {
            var startTime, endTime;
            var img = new Image();
            var testUrl = "https://www.google.com/favicon.ico"; // Small file to ping

            img.onload = function() {
                endTime = (new Date()).getTime();
                showPingResults();
            };

            startTime = (new Date()).getTime();
            img.src = testUrl + "?t=" + startTime; // Prevent caching

            function showPingResults() {
                var duration = endTime - startTime;
                document.getElementById('pingResult').innerHTML = `<strong>Ping time: ${duration}ms</strong>`;
            }
        }
        
        // feature section
        document.addEventListener('DOMContentLoaded', function() {
    // Screen Resolution and Color Depth
    function getScreenInfo() {
        var screenInfo = document.getElementById('screenInfo');
        screenInfo.innerHTML = `
            <strong>Screen Width:</strong> ${screen.width}px <br>
            <strong>Screen Height:</strong> ${screen.height}px<br>
            <strong>Color Depth:</strong> ${screen.colorDepth}-bit<br>
            <strong>Pixel Depth:</strong> ${screen.pixelDepth}-bit<br>
        `;
    }

    // Video Capabilities
    function checkVideoCapabilities() {
        var videoCapabilities = document.getElementById('videoCapabilities');
        var videoElement = document.createElement('video');
        var supportedFormats = [];

        var formats = [
            { type: 'video/mp4', codec: 'avc1.42E01E, mp4a.40.2' },
            { type: 'video/webm', codec: 'vp8, vorbis' },
            { type: 'video/ogg', codec: 'theora, vorbis' },
            { type: 'video/mp4', codec: 'hevc, mp4a.40.2' },
            { type: 'video/webm', codec: 'vp9' }
        ];

        formats.forEach(function(format) {
            if (videoElement.canPlayType(format.type + '; codecs="' + format.codec + '"') !== '') {
                supportedFormats.push(format.type + ' (' + format.codec + ')');
            }
        });

        videoCapabilities.innerHTML = '<p>Supported video formats:</p><ul>' + 
            supportedFormats.map(format => '<strong><li>' + format + '</li></strong>').join('') + 
            '</ul>';
    }

    // Call the functions
    getScreenInfo();
    checkVideoCapabilities();
});
    </script>
</body>
</html>