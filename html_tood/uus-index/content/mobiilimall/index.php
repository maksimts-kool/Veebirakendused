<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title>Tunniplaan</title>
 <link href="style.css" rel="stylesheet" type="text/css" />
 <script>
    function postForm(e, url) {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch(url, { method: "POST", body: formData })
        .then(response => response.text())
        .then(html => {
            document.getElementById("content-area").innerHTML = html;
        });
    }

    function loadPage(url) {
        fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById("content-area").innerHTML = html;
        });
    }

    window.addEventListener("DOMContentLoaded", () => {
        loadPage("content/mobiilimall/esmaspaev.php"); // default first page
    });
 </script>
</head>
<body>
<div id="schedule-content" class="header">
 <div id="schedule-content" class="nav">
 <ul>
   <li><a href="#" onclick="loadPage('content/mobiilimall/esmaspaev.php')">E</a></li>
   <li><a href="#" onclick="loadPage('content/mobiilimall/teisipaev.php')">T</a></li>
   <li><a href="#" onclick="loadPage('content/mobiilimall/kolmapaev.php')">K</a></li>
   <li><a href="#" onclick="loadPage('content/mobiilimall/neljapaev.php')">N</a></li>
   <li><a href="#" onclick="loadPage('content/mobiilimall/reede.php')">R</a></li>
 </ul>
 </div>
</div>
<div id="schedule-content" class="clear"></div>

<!-- This is where the page content will be loaded dynamically -->
<div id="schedule-content" class="content-area"></div>
</body>