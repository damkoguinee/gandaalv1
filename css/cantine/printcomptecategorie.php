<?php
require_once "lib/html2pdf.php";
ob_start(); ?>

<?php require '_header.php';?>

<style type="text/css">

body{
  margin: 0px;
  width: 100%;
  height:68%;
  padding:0px;}
  .ticket{
    margin:0px;
    width: 100%;
  }
  table {
    width: 100%;
    color: #717375;
    font-family: helvetica;
    line-height: 10mm;
    border-collapse: collapse;
    margin: auto;
  }
  
  .border th {
    border: 1px solid black;
    padding-bottom: 5px;
    padding-top: 5px;
    font-weight: bold;
    font-size: 12px;
    color: black;
    background: white;
    text-align: center; }
  .border td {
    line-height: 15mm;
    padding-bottom: 5px;
    padding-top: 5px;
    border: 1px solid black;    
    font-size: 12px;
    color: black;
    background: white;
    text-align: left;}
  .footer{
    font-size: 18px;
    font-style: italic;
  }

  .legende{
    font-size: 18px;
    text-align: center;
    padding-bottom: 5px;
    padding-top: 5px;
  }

</style>

<page backtop="5mm" backleft="10mm" backright="10mm" backbottom="5mm" footer="page;"><?php 

  $adress = $DB->querys('SELECT * FROM adresse ');?>

  <div class="ticket">

    <table style="width: 100%; margin:auto; text-align: center;color: black; background: white;" >

      <tr>
        <th style="font-weight: bold; font-size: 12px; padding: 5px"><?php echo $adress['nom_mag']; ?></th>
      </tr>

      <tr><td style="font-size: 12px;"><?=$adress['type_mag']; ?></td></tr>

      <tr><td><?=($adress['adresse']); ?></td></tr><br />
    </table>
  </div><?php

  if (isset($_GET['compteclient'])) {?>

    <table class="border">

      <thead>

        <tr>
          <th colspan="4">Compte Clients et Client-Fournisseurs</th>
        </tr>

        <tr>
          <th>N°</th>
          <th>Nom</th>
          <th>Solde Compte</th>
        </tr>

      </thead>

      <tbody><?php 
        $cumulmontant=0;

        $type1='Client';
        $type2='Clientf';

        if (isset($_GET['clientsearch'])) {
          $nomclient = $DB->query("SELECT *FROM client where id='{$_SESSION['reclient']}'");

          $panier=$nomclient;

        }else{

          $panier=$panier->clientF($type1, $type2);

          
        }

        foreach ($panier as $key => $value){

          $products= $DB->querys("SELECT sum(montant) as montant FROM bulletin where nom_client='{$value->id}' ");

          $cumulmontant+=$products['montant'];

          if ($products['montant']>0) {
            $color='red';
            $montant=$products['montant'];
          }else{

            $color='green';
            $montant=-$products['montant'];

          } ?>

          <tr>
            <td style="text-align: center; color: white;color: <?=$color;?> "><?=$key+1; ?></td>

            <td style="color: white; color: <?=$color;?>"><?= $value->nom_client; ?></td> 

            <td style="text-align: right; padding-right: 5px; color: white; color: <?=$color;?>"><?= number_format($montant,0,',',' '); ?></td>
          </tr><?php 
        }?>

      </tbody><?php 

      if ($cumulmontant<0) {
        $color='red';
        $cmontant=-$cumulmontant;
      }else{

        $color='green';
        $cmontant=$cumulmontant;

      }?>

      <tfoot>
          <tr>
            <th colspan="2">Totaux</th>
            <th style="text-align: right; padding-right: 5px; color: <?=$color;?>"><?= number_format($cmontant,0,',',' ');?></th>
          </tr>
      </tfoot>

    </table><?php
  }

  if (isset($_GET['comptepersonnel'])) {?>

    <table class="border">

      <thead>

        <tr>
          <th colspan="4">Compte Personnels</th>
        </tr>

        <tr>
          <th>N°</th>
          <th>Nom</th>
          <th>Solde Compte</th>
        </tr>

      </thead>

      <tbody><?php 
        $cumulmontant=0;

        $type1='Employer';
        $type2='Employer';

        if (isset($_GET['clientsearch'])) {
          $nomclient = $DB->query("SELECT *FROM client where id='{$_SESSION['reclient']}'");

          $panier=$nomclient;

        }else{

          $panier=$panier->clientF($type1, $type2);

          
        }

        foreach ($panier as $key => $value){

          $products= $DB->querys("SELECT sum(montant) as montant FROM bulletin where nom_client='{$value->id}' ");

          $cumulmontant+=$products['montant'];

          if ($products['montant']>0) {
            $color='red';
            $montant=$products['montant'];
          }else{

            $color='green';
            $montant=-$products['montant'];

          } ?>

          <tr>
            <td style="text-align: center; color: white;color: <?=$color;?> "><?=$key+1; ?></td>

            <td style="color: white; color: <?=$color;?>"><?= $value->nom_client; ?></td> 

            <td style="text-align: right; padding-right: 5px; color: white; color: <?=$color;?>"><?= number_format($montant,0,',',' '); ?></td>
          </tr><?php 
        }?>

      </tbody><?php 

      if ($cumulmontant<0) {
        $color='red';
        $cmontant=-$cumulmontant;
      }else{

        $color='green';
        $cmontant=$cumulmontant;

      }?>

      <tfoot>
          <tr>
            <th colspan="2">Totaux</th>
            <th style="text-align: right; padding-right: 5px; color: <?=$color;?>"><?= number_format($cmontant,0,',',' ');?></th>
          </tr>
      </tfoot>

    </table><?php
  }


  if (isset($_GET['compteautres'])) {?>

    <table class="border">

      <thead>

        <tr>
          <th colspan="4">Compte Autres</th>
        </tr>

        <tr>
          <th>N°</th>
          <th>Nom</th>
          <th>Solde Compte</th>
        </tr>

      </thead>

      <tbody><?php 
        $cumulmontant=0;

        $type1='autres';
        $type2='autres';

        if (isset($_GET['clientsearch'])) {
          $nomclient = $DB->query("SELECT *FROM client where id='{$_SESSION['reclient']}'");

          $panier=$nomclient;

        }else{

          $panier=$panier->clientF($type1, $type2);

          
        }

        foreach ($panier as $key => $value){

          $products= $DB->querys("SELECT sum(montant) as montant FROM bulletin where nom_client='{$value->id}' ");

          $cumulmontant+=$products['montant'];

          if ($products['montant']>0) {
            $color='red';
            $montant=$products['montant'];
          }else{

            $color='green';
            $montant=-$products['montant'];

          } ?>

          <tr>
            <td style="text-align: center; color: white;color: <?=$color;?> "><?=$key+1; ?></td>

            <td style="color: white; color: <?=$color;?>"><?= $value->nom_client; ?></td> 

            <td style="text-align: right; padding-right: 5px; color: white; color: <?=$color;?>"><?= number_format($montant,0,',',' '); ?></td>
          </tr><?php 
        }?>

      </tbody><?php 

      if ($cumulmontant<0) {
        $color='red';
        $cmontant=-$cumulmontant;
      }else{

        $color='green';
        $cmontant=$cumulmontant;

      }?>

      <tfoot>
          <tr>
            <th colspan="2">Totaux</th>
            <th style="text-align: right; padding-right: 5px; color: <?=$color;?>"><?= number_format($cmontant,0,',',' ');?></th>
          </tr>
      </tfoot>

    </table><?php
  }

  if (isset($_GET['comptefournisseur'])) {?>

    <table class="border">

      <thead>

        <tr>
          <th colspan="4">Compte Fournisseurs</th>
        </tr>

        <tr>
          <th>N°</th>
          <th>Nom</th>
          <th>Solde Compte</th>
        </tr>

      </thead>

      <tbody><?php 
        $cumulmontant=0;

        $type1='Fournisseur';
        $type2='Fournisseur';

        if (isset($_GET['clientsearch'])) {
          $nomclient = $DB->query("SELECT *FROM client where id='{$_SESSION['reclient']}'");

          $panier=$nomclient;

        }else{

          $panier=$panier->clientF($type1, $type2);

          
        }

        foreach ($panier as $key => $value){

          $products= $DB->querys("SELECT sum(montant) as montant FROM bulletin where nom_client='{$value->id}' ");

          $cumulmontant+=$products['montant'];

          if ($products['montant']>0) {
            $color='red';
            $montant=$products['montant'];
          }else{

            $color='green';
            $montant=-$products['montant'];

          } ?>

          <tr>
            <td style="text-align: center; color: white;color: <?=$color;?> "><?=$key+1; ?></td>

            <td style="color: white; color: <?=$color;?>"><?= $value->nom_client; ?></td> 

            <td style="text-align: right; padding-right: 5px; color: white; color: <?=$color;?>"><?= number_format($montant,0,',',' '); ?></td>
          </tr><?php 
        }?>

      </tbody><?php 

      if ($cumulmontant>0) {
        $color='red';
        $cmontant=$cumulmontant;
      }else{

        $color='green';
        $cmontant=-$cumulmontant;

      }?>

      <tfoot>
          <tr>
            <th colspan="2">Totaux</th>
            <th style="text-align: right; padding-right: 5px; color: <?=$color;?>"><?= number_format($cmontant,0,',',' ');?></th>
          </tr>
      </tfoot>

    </table><?php
  }?>
    

</page>
<?php
  $content = ob_get_clean();
  try {
    $pdf = new HTML2PDF("p","A4","fr", true, "UTF-8" , 0);
    $pdf->pdf->SetAuthor('Amadou');
    $pdf->pdf->SetTitle(date("d/m/y"));
    $pdf->pdf->SetSubject('Création d\'un Portfolio');
    $pdf->pdf->SetKeywords('HTML2PDF, Synthese, PHP');
    $pdf->pdf->IncludeJS("print(true);");
    $pdf->writeHTML($content);
    $pdf->Output('COMPTE EDITE'.date("d/m/y").date("H:i:s").'.pdf');
    // $pdf->Output('Devis.pdf', 'D');    
  } catch (HTML2PDF_exception $e) {
    die($e);
  }
?>