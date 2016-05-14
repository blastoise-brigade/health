<?php

function showLogin() {
 ?>
 <!DOCTYPE HTML>
 <html>
   <head>
     <title>Presky</title>
     <style>
     #img_container {
         position:relative;
         display:inline-block;
         text-align:center;
         border:1px solid red;
     }

     .button {
         position:absolute;

         bottom:10px;
         right:1000px;
         width:100px;
         height:3000px;
     }

     body {
       background: url(https://dl2.pushbulletusercontent.com/Jff8LlmtNWEtgfTpc4OBMaOS39KcZpNz/PolyGenPattern_17320161298.png);
       background-size: 100%;
       background-attachment: cover;
     }
     </style>
     <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-aNUYGqSUL9wG/vP7+cWZ5QOM4gsQou3sBfWRr/8S3R1Lv0rysEmnwsRKMbhiQX/O" crossorigin="anonymous">
     <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
     <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-OiWEn8WwtH+084y4yW2YhhH6z/qTSecHZuk/eiWtnvLtU+Z8lpDsmhOKkex6YARr" crossorigin="anonymous">
     <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
   </head>
   <body>
     <div class="jumbotron" style="background: linear-gradient(to bottom, #7A98BF , #9CBCD9);">
       <div class="container">
         <h1><a href='./' style="color: #FFF;">Presky</a></h1>

       </div>
     </div>
 <div class="container">
   <div class="row">
     <div class="col-md-4">
       <h1 style="color: #fff"; font-family="Montserrat"; font-size="71px">Login Using:</h1>
       <button class="btn btn-default" data-toggle="modal" data-target="#ssoModal"><i class="fa fa-envelope"></i> SSO (Single Sign On) Link</button>
     </div>
     <div class="col-md-2">
     </div>
     <div class="col-md-6">
     </div>
   </div>
  </div>
 <div id="ssoModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title"><i class="fa fa-envelope"></i> SSO (Single Sign On) Link</h4>
       </div>
       <div class="modal-body">
         <p>Please input your email address here.</p>
         <form action="./login" method="post">
           <div class="form-group">
             <label for="email">Email address:</label>
             <input type="email" name="email" class="form-control" id="email">
           </div>
           <input type="submit" value="Login" class="btn btn-success">
         </form>
       </div>
     </div>
   </div>
 </div>
</body>
</html>
 <?php } ?>
