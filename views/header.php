<?php

function showHeader() {
  ?>

  <!DOCTYPE html>
  <html lang="en">

  <head>

      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">

      <title>Presky Admin Panel</title>

      <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
      <link href="assets/css/bootstrap.min.css" rel="stylesheet">

      <!-- Custom CSS -->
      <link href="assets/css/freelancer.css" rel="stylesheet">

      <!-- Custom Fonts -->
      <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
      <link href="assets/css/customFonts.css" rel="stylesheet" type="text/css">

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <style>
      header {
        background-image: url("./assets/user-cover.png");
      }
      span.glyphcustom {
        font-size: 126px;
        color: #666;
        padding: 5%;
      }
      table.table-hover tr:hover {
        background-color: #222;
      }
      </style>

  </head>

  <body id="page-top" class="index">

      <!-- Navigation -->
      <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container">
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header page-scroll">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand"; font-size="100px"; href="./">Presky</a>
              </div>

              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav navbar-right">
                      <li>
                          <button class="btn btn-info" id="demo" onclick="sync()">Sync to ShineOS+</button>
                      </li>
                      <li class="page-scroll">
                          <a href="./search">Search</a>
                      </li>
                      <li class="page-scroll">
                          <a href="./table">Table Data</a>
                      </li>
                      <li class="page-scroll">
                          <a href="./graph.html">Graph</a>
                      </li>
                      <li class="page-scroll">
                          <a href="./logout">Log Out</a>
                      </li>
                  </ul>
              </div>
              <!-- /.navbar-collapse -->
          </div>
          <!-- /.container-fluid -->
      </nav>

      <script>
      var a = 0;
      function sync() {
        if (a == 0) {
          a = 1;
          var xhttp = new XMLHttpRequest();
          document.getElementById("demo").innerHTML = "<i class='fa fa-refresh fa-spin' aria-hidden='true'></i> Syncing...";
          xhttp.open("GET", "./shineossync.php", true);
          xhttp.send();
          setTimeout(function () {
            document.getElementById("demo").innerHTML = "<i class='fa fa-check-square-o' aria-hidden='true'></i> Success.";
            a = 0;
          }, 10000);
        }
      };
    </script>
  <?php
}

 ?>
