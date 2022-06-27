<!DOCTYPE html>
<html>
<head>
  <script src="YourScript.js"></script>
  <link href="1.css" rel="stylesheet" type="text/css" media="screen" />
  <style>
  	body {font-family: "Lato", sans-serif;}
		.sidenav {height: 100%;width: 200px;position: fixed;z-index: 1;top: 0;left: 0;background-color: #464850;overflow-x: hidden;padding-top: 20px;}
		.sidenav a {padding: 6px 8px 6px 16px;text-decoration: none;font-size: 25px;color: #818181;display: block;}
		.sidenav a:hover {color: #f1f1f1;}
		.main {margin-left: 200px; /* Same as the width of the sidenav */font-size: 20px; /* Increased text to enable scrolling */padding: 0px 10px;}
		@media screen and (max-height: 450px) {
  		.sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}}
  </style>
</head>
  
<body>
	<div class="sidenav">
      <button onclick="Link1()" class="buttonActivMenu">                <h3>Link 1</h3></button>
  	  <button onclick="Link2()" class="buttonMenuWithUndercategory"><a href="">       <h3>link2</h3></a></button>
	  <button onclick="Link2_1()" class="buttonSecondMenu"><a href="">                <h3>link2.1</h3></a></button>
	  <button onclick="Link2_2()" class="buttonLastSecondMenu"><a href="">            <h3>link2.2</h3></a></button>
	</div>

	<div class="main">
      Your text and images here
    </div>
<script src="1.js"></script>
</body>
</html>