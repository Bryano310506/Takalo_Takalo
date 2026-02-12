<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <title>Profile</title>
</head>
<body>
    <?= $session['nom'] ?> <?= $session['prenom'] ?>
    <?php for($i=0; $i<count($list_objets); $i++) { ?>
        <?php if(count($list_objets) == 0) { ?>
            <h2>Aucun elements</h2>
        <?php } else { ?>
            
        <?php } ?>
    <?php } ?>
     <section class="product_section layout_padding">
         <div class="container">
            <div class="heading_container heading_center">
               <h2>
                  Our <span>products</span>
               </h2>
            </div>rr
            <div class="row">
               <div class="col-sm-6 col-md-4 col-lg-4">
                  <div class="box">
                     <div class="option_container">
                        <div class="options">
                           <a href="" class="option1">
                           Add To Cart
                           </a>
                           <a href="" class="option2">
                           Buy Now
                           </a>
                        </div>
                     </div>
                     <div class="img-box">
                        <img src="img/p10.png" alt="">
                     </div>
                     <div class="detail-box">
                        <h5>
                           <?= $list_objets[$i]['titre']?>
                        </h5>
                        <h6>
                           $<?= $list_objets[$i]['prix']?>
                        </h6>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
</body>
</html>