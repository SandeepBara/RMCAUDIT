<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>No internet</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      color: #333;
      background: #fff;
    }
    .main-content {
      margin: 100px auto;
      width: 600px;
      max-width: 90%;
      text-align: left;
    }
    .icon {
      font-size: 80px;
      margin-bottom: 20px;
    }
    .dino {
      width: 72px;
      height: 72px;
      background: url('https://ssl.gstatic.com/chrome/components/dino/dist/img/offline-sprite.png') no-repeat;
      background-position: 0 0;
      background-size: 72px 72px;
    }
    h1 {
      font-weight: normal;
      font-size: 24px;
      margin: 0 0 16px;
    }
    ul {
      margin-top: 8px;
      padding-left: 20px;
    }
    ul li {
      margin-bottom: 4px;
    }
    a {
      color: #1a73e8;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    .error {
      margin-top: 16px;
      color: #5f6368;
      font-size: 12px;
    }
  </style>
</head>
<body>
  <div class="main-content">
    <div class="dino"></div>
    <h1>No internet</h1>
    <div>Try:</div>
    <ul>
      <li>Checking the network cables, modem, and router</li>
      <li>Reconnecting to Wi-Fi</li>
      <li><a href="#">Running Windows Network Diagnostics</a></li>
    </ul>
    <div class="error">ERR_INTERNET_DISCONNECTED</div>
  </div>
</body>
</html>
