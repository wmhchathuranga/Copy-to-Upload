 <?php
    session_start();
    ?>

 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>PictureBin | Home</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
     <link rel="stylesheet" href="./res/css/upload.css">
 </head>

 <body>

     <nav class="navbar navbar-expand-lg bg-dark position-absolute w-100 py-2 shadow-medium">
         <div class="container">
             <a class="navbar-brand fs-3" href="#"><span class="text-green fst-italic">Picture</span><span class="fst-italic text-secondary">Bin</span></a>
             <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                 <span class="navbar-toggler-icon"></span>
             </button>
             <div class="collapse navbar-collapse row justify-content-center" id="navbarNavAltMarkup">
                 <div class="navbar-nav col-5 gap-2">
                     <a class="nav-link fs-5 text-secondary text-green" aria-current="page" href="./">Home</a>
                     <a class="nav-link fs-5 text-secondary" href="./gallery.html">My Gallery</a>
                     <a class="nav-link fs-5 text-secondary" href="./about.html">About</a>
                 </div>
             </div>
         </div>
     </nav>

     <main>

         <div id="FileUpload">
             <div class="wrapper" id="wrapper">
                 <div class="upload">
                     <p>Paste files here or <span class="upload__button" id="upload_btn" onclick="upload_btn()">Browse</span>
                     </p>
                     <form method="post" action="upload.php" enctype="multipart/form-data" id="uploadForm" hidden>
                         <input type="file" name="file" id="file" accept="image/*" />
                     </form>
                 </div>

                 <div class="uploaded uploaded--one row mx-auto" id="uploaded">
                 </div>
                 <div class="count text-danger text-center" id="remaining">
                     <p class="m-0">Remaining Images : <span id="count">3</span></p>
                 </div>
                 <div class="text-center my-2 d-none" id="submit">
                     <button class="btn btn-success w-50" onclick="addToGallery()">Add to Gallery</button>
                 </div>

             </div>
         </div>
     </main>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
     <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/core.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/md5.js"></script>
     <script src="./res/js/upload.js"> </script>
 </body>

 </html>