<?php
class panier{

	private $DB;

	public $monnaie=['gnf'];

	public $phone=['+33766393353','+33753542292'];

	public $email=['dialphafr@gmail.com', 'djoumadiallo@gmail.com', 'codebar224@gmail.com'];
	public $modep=['espèces','chèque','virement'];

	public function __construct($DB){
		if(!isset($_SESSION)){
			session_start();
		}
		
		if(!isset($_SESSION['panier'])){
			$_SESSION['panier'] = array();
		}

		if(!isset($_SESSION['panierp'])){
			$_SESSION['panierp'] = array();
		}

		if(!isset($_SESSION['paniermenub'])){
			$_SESSION['paniermenub'] = array();
		}

		if(!isset($_SESSION['paniermenud'])){
			$_SESSION['paniermenud'] = array();
		}

		if(!isset($_SESSION['paniermenuc'])){
			$_SESSION['paniermenuc'] = array();
		}

		if(!isset($_SESSION['panierb'])){
			$_SESSION['panierb'] = array();
		}

		if(!isset($_SESSION['panierd'])){
			$_SESSION['panierd'] = array();
		}

		if(!isset($_SESSION['panierc'])){
			$_SESSION['panierc'] = array();
		}

		

		$this->DB = $DB;


		if(isset($_GET['delPanier'])){
			$this->del($_GET['delPanier']);
		}
		if(isset($_GET['delpaniermenub'])){
			$this->delmb($_GET['delpaniermenub']);
		}

		if(isset($_GET['delpaniermenud'])){
			$this->delmd($_GET['delpaniermenud']);
		}

		if(isset($_GET['delpaniermenuc'])){
			$this->delmc($_GET['delpaniermenuc']);
		}


		if(isset($_GET['delPanierb'])){
			$this->delb($_GET['delPanierb']);
		}
		if(isset($_GET['delPanierd'])){
			$this->deld($_GET['delPanierd']);
		}

		if(isset($_GET['delPanierc'])){
			$this->delc($_GET['delPanierc']);
		}


		if(isset($_GET['delPanierp'])){
			$this->delp($_GET['delPanierp']);
		}

		if(isset($_POST['panier']['quantity'])){
			$this->recalc();
		}

		if(isset($_POST['panierp']['quantity'])){
			$this->recalcp();
		}

		if(isset($_POST['panierb']['quantity'])){
			$this->recalcb();
		}

		if(isset($_POST['panierd']['quantity'])){
			$this->recalcd();
		}

		if(isset($_POST['panierc']['quantity'])){
			$this->recalcc();
		}
	}

	public function espace($value){
			return str_replace(' ', '', $value);
		}

		public function h(string $value):string{
			return htmlentities($value);
		}

		public function formatDate($value){
			return((new DateTime($value))->format("d/m/Y à H:i"));
		}
	public function color($value){

		if ($value>0) {
          $color='red';
        }else{

          $color='green';

        }
		return($color);
	}

	public function colorSigne($value){

		if ($value>0) {
          $color='red';
          $signe='-';
          $signe1=1;
        }else{

          $color='green';
          $signe='+';
          $signe1=-1;

        }
		return array($color, $signe, $signe1);
	}

	public function listeProduit(){
		$nomclient = $this->DB->query("SELECT *FROM stock ORDER BY (nom)");

		return $nomclient;
	}

	public function nomProduit($nom){
		$nomclient = $this->DB->querys("SELECT nom FROM stock where id='{$nom}'");

		return ucwords($nomclient['nom']);
	}

	public function nomIngredient($nom){
		$nomclient = $this->DB->querys("SELECT nom, taille FROM stock where id='{$nom}'");

		return ucwords($nomclient['nom']);
	}

	public function nomProduitIngredient($nom){
		$nomclient = $this->DB->querys("SELECT nom, taille FROM stock where id='{$nom}'");

		return array(ucwords($nomclient['nom'].' '.$nomclient['taille']));
	}

	public function ingredient(){
		$nomclient = $this->DB->query("SELECT id, nom, qtite FROM ingredient");

		return $nomclient;
	}

	public function client(){
		$prodclient = $this->DB->query('SELECT * FROM client order by(nom_client) ');

		return $prodclient;
	}

	public function clientF($type1, $type2){

		$prodclient = $this->DB->query("SELECT * FROM client where type='{$type1}' or type='{$type2}' order by(nom_client)");

		return $prodclient;
	}

	public function ClientT($type){
		
		$prodclient = $this->DB->query("SELECT * FROM client where typeclient='{$type}' order by(nom_client)");

		return $prodclient;
	}

	public function nomClient($nom){
		$nomclient = $this->DB->querys("SELECT nom_client, telephone, adresse, mail FROM client where id='{$nom}'");

		return ucwords($nomclient['nom_client']);
	}

	public function nomClientad($nom){
		$nomclient = $this->DB->querys("SELECT nom_client, telephone, adresse, mail FROM client where id='{$nom}'");

		return array(ucwords($nomclient['nom_client']), ucwords($nomclient['telephone']), ucwords($nomclient['adresse']), strtolower($nomclient['mail']));
	}

	public function adClient($nom){
			$nomclient = $this->DB->querys("SELECT nom_client, telephone, adresse, type FROM client where id='{$nom}'");

			return array(ucwords($nomclient['nom_client']), $nomclient['telephone'], ucwords($nomclient['adresse']), strtolower($nomclient['type'])); 
		}

	public function listePersonnel(){
		$type='admin';

	  $reqliste=$this->DB->query("SELECT *from personnel where statut!='{$type}'");

	  return $reqliste;
	}

	public function nomPersonnel($nom){
		$nomclient = $this->DB->querys("SELECT *FROM personnel where id='{$nom}'");

		return array(ucwords($nomclient['pseudo']), $nomclient['nom']);
	}

	public function nomBanque(){// Permet de recuperer un evenement

		$prod=$this->DB->query("SELECT nomb, id, numero FROM nombanque");

		return $prod;

	}

	public function compteClient($client){
			$prodcompte = $this->DB->querys("SELECT sum(montant) as montant FROM bulletin where nom_client='{$client}' ");

			return $prodcompte['montant'];
		}

	public function montantCompte($banque){

		$prod=$this->DB->querys("SELECT sum(montant) as montant FROM banque where id_banque='{$banque}' ");

		return $prod['montant'];

	}

	public function nomBanqueCaisse(){// Permet de recuperer un evenement

		$prod=$this->DB->query("SELECT nomb, id, numero FROM nombanque order by(id)");

		return $prod;

	}

	public function soldeBanque(){

		$banque=1;

		$prodnbre =$this->DB->querys("SELECT sum(montant) as montant FROM banque where id_banque!='{$banque}'");

		return ($prodnbre['montant']);
	}

	public function soldeBanqueU($param){

		$prodnbre =$this->DB->querys("SELECT sum(montant) as montant FROM banque where id_banque='{$param}'");

		return ($prodnbre['montant']);
	}

	public function soldecredit(){
		$etat='credit';

		$prodsolde=$this->DB->querys('SELECT sum(montant) as montant FROM bulletin');

		//$product = $this->DB->querys('SELECT sum(reste) as reste FROM payement WHERE etat=:ETAT AND typeclient!=:TYPE AND YEAR(date_cmd) = :ANNEE', array('ETAT' => $etat, 'TYPE'=>"VIP", 'ANNEE'=>$_SESSION['date']));
		$product['reste']=0;

		if ($prodsolde['montant']>0) {
			$solde=-$prodsolde['montant'];
		}else{

			$solde=$prodsolde['montant'];

		}

		return $solde+$product['reste'];			
	}

	public function soldeclient(){
    	$solde=0;
    	$prod =$this->DB->query('SELECT sum(montant) as montant FROM bulletin WHERE nom_client= :CLIENT',array(
        'CLIENT' => $_SESSION['nameclient']));
  	

      	foreach ($prod as $product ){

            $solde=$product->montant;

        }

        return $solde;
    }

    public function soldeclientgnf($client){
    	$solde=0;
    	$prod =$this->DB->query('SELECT sum(montant) as montant FROM bulletin WHERE nom_client= :CLIENT',array(
        'CLIENT' => $client));
  	

      	foreach ($prod as $product ){

            $solde=$product->montant;

        }

        return $solde;
    }

    public function totdepense($date1){//permet de recuperer le prix total des depenses

		$prodnbre =$this->DB->querys("SELECT sum(montant) as tot FROM decdepense WHERE DATE_FORMAT(date_payement, \"%Y\")='{$date1}'");


		$prodloyer =$this->DB->querys("SELECT sum(montant) as tot FROM decloyer WHERE DATE_FORMAT(date_payement, \"%Y%m%d\") >='{$date1}'");

		$prodpers =$this->DB->querys("SELECT sum(montant) as tot FROM decpersonnel WHERE DATE_FORMAT(date_payement, \"%Y%m%d\") >='{$date1}'");

		return ($prodnbre['tot']+$prodloyer['tot']+$prodpers['tot']);
	}

	public function total(){	

		$prodpaie = $this->DB->querys("SELECT sum(pvente*quantite) as ptotal FROM validpaie where pseudov='{$_SESSION['idpseudo']}'");
		$prodvente = $this->DB->querys("SELECT remise, montantpgnf FROM validvente where pseudop='{$_SESSION['idpseudo']}'");

		$total= $prodpaie['ptotal']-$prodvente['remise']-$prodvente['montantpgnf'];
		
		return $total;
	}

	public function totalpaye(){	
		$prodvente = $this->DB->querys("SELECT montantpgnf FROM validvente where pseudop='{$_SESSION['idpseudo']}'");

		$total=$prodvente['montantpgnf'];
		
		return $total;
	}

	public function totalcom(){	

		$prodpaie = $this->DB->querys("SELECT sum(pvente*quantite) as ptotal FROM validpaie where pseudov='{$_SESSION['idpseudo']}'");

		$total= $prodpaie['ptotal'];
		
		return $total;
	}

	public function totalmodif(){	

		$prodpaie = $this->DB->querys("SELECT sum(pvente*quantite) as ptotal FROM validpaiemodif where pseudov='{$_SESSION['idpseudo']}'");
		$prodvente = $this->DB->querys("SELECT remise, montantpgnf FROM validventemodif where pseudop='{$_SESSION['idpseudo']}'");

		$total= $prodpaie['ptotal']-$prodvente['remise']-$prodvente['montantpgnf'];
		
		return $total;
	}

	public function totalpayemodif(){	
		$prodvente = $this->DB->querys("SELECT montantpgnf FROM validventemodif where pseudop='{$_SESSION['idpseudo']}'");

		$total=$prodvente['montantpgnf'];
		
		return $total;
	}

	public function totalcommodif(){	

		$prodpaie = $this->DB->querys("SELECT sum(pvente*quantite) as ptotal FROM validpaiemodif where pseudov='{$_SESSION['idpseudo']}'");

		$total= $prodpaie['ptotal'];
		
		return $total;
	}

	public function totalcomTable(){	

		$prodpaie = $this->DB->querys("SELECT sum(pvente*quantite) as ptotal FROM tablecommande where idtable='{$_SESSION['tableresto']}' and pseudov='{$_SESSION['idpseudo']}'");

		$total= $prodpaie['ptotal'];
		
		return $total;
	}

	public function totalCommandeSurplace(){	

		$prodpaie = $this->DB->querys("SELECT sum(pvente*quantite) as ptotal FROM tablecommande where pseudov='{$_SESSION['idpseudo']}'");

		$total= $prodpaie['ptotal'];
		$totalformat=number_format($total,0,',',' ');
		
		return array($total, $totalformat);
	}

	public function totaltable(){	

		$prodpaie = $this->DB->querys("SELECT sum(pvente*quantite) as ptotal FROM tablecommande where idtable='{$_SESSION['tableresto']}' and pseudov='{$_SESSION['idpseudo']}'");
		$prodvente = $this->DB->querys("SELECT remise, montantpgnf FROM tablevalide where idtable='{$_SESSION['tableresto']}' and pseudop='{$_SESSION['idpseudo']}'");

		$total= $prodpaie['ptotal']-$prodvente['remise']-$prodvente['montantpgnf'];
		
		return $total;
	}

	public function totalpayetable(){	
		$prodvente = $this->DB->querys("SELECT montantpgnf FROM tablevalide where idtable='{$_SESSION['tableresto']}' and pseudop='{$_SESSION['idpseudo']}'");

		$total=$prodvente['montantpgnf'];
		
		return $total;
	}

	public function nbreVente($date1, $date2):int{//permet de recuperer le nombre de ventes

		if (isset($_POST['magasin']) and $_POST['magasin']=='general') {
			
			$prodnbre =$this->DB->querys("SELECT Count(num_cmd) as nbre FROM payement WHERE DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}'");

		}elseif (isset($_POST['magasin']) and $_POST['magasin']!='general') {

			$prodnbre =$this->DB->querys("SELECT Count(num_cmd) as nbre FROM payement WHERE vendeur='{$_POST['magasin']}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}'");
		}else{

			$prodnbre =$this->DB->querys("SELECT Count(num_cmd) as nbre FROM payement WHERE vendeur='{$_SESSION['idpseudo']}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}'");
		}		

		return $prodnbre['nbre'];
	}

	public function venteTot($date1, $date2){//permet de recuperer le prix total des ventes

		if (isset($_POST['magasin']) and $_POST['magasin']=='general') {
			
			$prodnbre =$this->DB->querys("SELECT sum(Total) as tot, sum(fraisup) as frais, sum(remise) as remise FROM payement WHERE DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}'");

		}elseif (isset($_POST['magasin']) and $_POST['magasin']!='general') {

			$prodnbre =$this->DB->querys("SELECT sum(Total) as tot, sum(fraisup) as frais, sum(remise) as remise FROM payement WHERE vendeur='{$_POST['magasin']}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}'");
		}else{
			
			$prodnbre =$this->DB->querys("SELECT sum(Total) as tot, sum(fraisup) as frais, sum(remise) as remise FROM payement WHERE vendeur='{$_SESSION['idpseudo']}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}'");
		}

		return ($prodnbre['tot']-$prodnbre['remise']-$prodnbre['frais']);
	}

	public function depenseTot($date1, $date2){//permet de recuperer le prix total des depenses

		$prodnbre =$this->DB->querys("SELECT sum(montant) as tot FROM decdepense WHERE DATE_FORMAT(date_payement, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_payement, \"%Y%m%d\") <= '{$date2}'");

		$prodloyer =$this->DB->querys("SELECT sum(montant) as tot FROM decloyer WHERE DATE_FORMAT(date_payement, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_payement, \"%Y%m%d\") <= '{$date2}'");

		$prodpers =$this->DB->querys("SELECT sum(montant) as tot FROM decpersonnel WHERE DATE_FORMAT(date_payement, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_payement, \"%Y%m%d\") <= '{$date2}'");

		return ($prodnbre['tot']+$prodloyer['tot']+$prodpers['tot']);
	}

	public function benefice($date1, $date2){//permet de recuperer lebenefice

		$prodnbre =$this->DB->querys("SELECT sum(prix_vente*quantity) as pv, sum(prix_revient*quantity) as pr FROM commande inner join payement on commande.num_cmd=payement.num_cmd WHERE DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}'");

		$prodnbreben =$this->DB->querys("SELECT sum(remise) as remise FROM payement WHERE DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}'");

		$benefice=$prodnbre['pv']-$prodnbre['pr']-$prodnbreben['remise']-$this->depenseTot($date1, $date2);

		return ($benefice);
	}

	public function versementC($date1, $date2){//permet de recuperer le total des versements

		$prodnbre =$this->DB->querys("SELECT sum(montant) as montant FROM versement WHERE DATE_FORMAT(date_versement, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_versement, \"%Y%m%d\") <= '{$date2}'");

		return ($prodnbre['montant']);
	}

	public function remboursementC($date1, $date2, $provenance){//permet dles remboursements du jour

		$prodnbre =$this->DB->querys("SELECT sum(montant) as montant FROM banque WHERE provenance='{$provenance}' and DATE_FORMAT(date_versement, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_versement, \"%Y%m%d\") <= '{$date2}'");

		return ($prodnbre['montant']);
	}

	public function creditF($date1, $date2){//permet les credits du magasin

		$prodnbre =$this->DB->querys("SELECT sum(montantht) as montht, sum(montantva) as montva, sum(montantpaye) as montp FROM facture WHERE DATE_FORMAT(datecmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(datecmd, \"%Y%m%d\") <= '{$date2}'");

		return ($prodnbre['montht']+$prodnbre['montva']-$prodnbre['montp']);
	}

	public function montantCloture($date1){//permet le montant de la fermeture 

		$prodnbre =$this->DB->querys("SELECT sum(tot_saisie) as montant FROM cloture WHERE DATE_FORMAT(date_cloture, \"%Y%m%d\") <='{$date1}'");

		return ($prodnbre['montant']);
	}

	public function totalcaisse(){

		$totalcaisse=0;
		$now = date('Y-m-d');
      	$now = new DateTime( $now );
      	$now = $now->format('Ymd');

      	$prodenc=$this->DB->querys('SELECT SUM(Total) AS totp, SUM(remise) AS remp, sum(fraisup) as frais, mode_payement FROM payement WHERE DATE_FORMAT(date_cmd, \'%Y%m%d\')<= :NOW ', array(
        'NOW' => $now
      	));

       $totenc=($prodenc['totp']-$prodenc['remp']-$prodenc['frais']);
       

      	$reste_payer=$this->DB->querys('SELECT SUM(Total) AS totpc, SUM(remise) AS rempc, SUM(montantpaye) AS montpc, SUM(reste) AS respc, mode_payement FROM payement WHERE DATE_FORMAT(date_cmd, \'%Y%m%d\')<= :NOW AND etat= :Etat ', array(
        'NOW' => $now,
        'Etat'=>'credit'
      ));    
      $credclient_gnf=$reste_payer['respc'];

      $decumul =$this->DB->querys('SELECT SUM(montant) AS montdg FROM decaissement WHERE DATE_FORMAT(date_payement, \'%Y%m%d\')<= :NOW and cprelever!=:compte', array('NOW' => $now, 'compte'=>36));

      $decdepcumul =$this->DB->querys('SELECT SUM(montant) AS montdg FROM decdepense WHERE DATE_FORMAT(date_payement, \'%Y%m%d\')<= :NOW and cprelever!=:compte', array('NOW' => $now, 'compte'=>36));

      $decloycumul =$this->DB->querys('SELECT SUM(montant) AS montdg FROM decloyer WHERE DATE_FORMAT(date_payement, \'%Y%m%d\')<= :NOW and cprelever!=:compte', array('NOW' => $now, 'compte'=>36));

      $decperscumul =$this->DB->querys('SELECT SUM(montant) AS montdg FROM decpersonnel WHERE DATE_FORMAT(date_payement, \'%Y%m%d\')<= :NOW and cprelever!=:compte', array('NOW' => $now, 'compte'=>36));

      $frais =$this->DB->querys('SELECT SUM(frais) as frais, sum(montantpaye) AS montf FROM facture WHERE DATE_FORMAT(datecmd, \'%Y%m%d\')<= :NOW ', array('NOW' => $now));

      //$fraisup =$DB->querys('SELECT SUM(montant) AS montfsup FROM fraisup WHERE DATE_FORMAT(date_payement, \'%Y%m%d\')<= :NOW', array('NOW' => $now));

      $montdec_gnf=$decumul['montdg']+$decdepcumul['montdg']+$decloycumul['montdg']+$decperscumul['montdg']+($frais['montf']+$frais['frais']);

      

      $date1=$now;
      if (!empty($this->montantCloture($date1))) {
        $totalcaisse=$this->montantCloture($date1);
      }else{
        $totalcaisse=($totenc-$credclient_gnf+$this->versementgnf()-$montdec_gnf)+ ($this->manque());
      }

		return $totalcaisse;			
	}

	public function fondCaisse(){

		$prodnbre =$this->DB->querys("SELECT sum(montant) as montant FROM banque");

		return ($this->totalcaisse());
	}

	public function versementgnf(){
			
		$versementgnf=0;
		

		$products = $this->DB->query('SELECT SUM(montant) AS sommeverse, date_versement FROM versement where comptedep!=:compte', array('compte'=>36));

		foreach( $products as $versement ) {

			$versementgnf = $versement->sommeverse ;

		}

		return $versementgnf;
		
	}

	public function nbreventetotstat($id){	
		$prodvente = $this->DB->querys("SELECT sum(quantity) as qtite FROM commande where id_produit='{$id}'");
		
		return $prodvente['qtite'];
	}


	public function nbreprodstatpardate($id, $date1, $date2){

		if (isset($_POST['j1'])) {	
			$prodvente = $this->DB->querys("SELECT sum(quantity) as qtite FROM commande inner join payement on commande.num_cmd=payement.num_cmd where DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}' and commande.id_produit='{$id}'");
		}else{

			$date1=date("Y");

			$prodvente = $this->DB->querys("SELECT sum(quantity) as qtite FROM commande inner join payement on commande.num_cmd=payement.num_cmd where DATE_FORMAT(date_cmd, \"%Y%\") >='{$date1}' and commande.id_produit='{$id}'");

		}
		
		return $prodvente['qtite'];
	}

	public function beneficeprodstatpardate($id, $date1, $date2){

		if (isset($_POST['j1'])) {	
			$prodvente = $this->DB->querys("SELECT sum((quantity*prix_vente)-(quantity*prix_revient)) as benefice FROM commande inner join payement on commande.num_cmd=payement.num_cmd where DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}' and commande.id_produit='{$id}'");
		}else{

			$date1=date("Y");

			$prodvente = $this->DB->querys("SELECT sum((quantity*prix_vente)-(quantity*prix_revient)) as benefice FROM commande inner join payement on commande.num_cmd=payement.num_cmd where DATE_FORMAT(date_cmd, \"%Y%\") >='{$date1}' and commande.id_produit='{$id}'");

		}
		
		return $prodvente['benefice'];
	}

	public function montantstatpardate($id, $date1, $date2){

		if (isset($_POST['j1'])) {	
			$prodvente = $this->DB->querys("SELECT sum(prix_vente*quantity) as qtite FROM commande inner join payement on commande.num_cmd=payement.num_cmd where DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}' and id_client='{$id}'");
		}else{

			$date1=date("Y");

			$prodvente = $this->DB->querys("SELECT sum(prix_vente*quantity) as qtite FROM commande inner join payement on commande.num_cmd=payement.num_cmd where DATE_FORMAT(date_cmd, \"%Y%\") >='{$date1}' and id_client='{$id}'");

		}
		
		return $prodvente['qtite'];
	}

	public function beneficestatpardate($id, $date1, $date2){

		if (isset($_POST['j1'])) {	
			$prodvente = $this->DB->querys("SELECT sum((prix_vente*quantity)-(prix_revient*quantity)) as benefice FROM commande inner join payement on commande.num_cmd=payement.num_cmd where DATE_FORMAT(date_cmd, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_cmd, \"%Y%m%d\") <= '{$date2}' and id_client='{$id}'");
		}else{

			$date1=date("Y");

			$prodvente = $this->DB->querys("SELECT sum((prix_vente*quantity)-(prix_revient*quantity)) as benefice FROM commande inner join payement on commande.num_cmd=payement.num_cmd where DATE_FORMAT(date_cmd, \"%Y%\") >='{$date1}' and id_client='{$id}'");

		}
		
		return $prodvente['benefice'];
	}

	public function count(){
		return array_sum($_SESSION['panier']);
	}

	

	
	public function totalp(){
		$totalp = 0;
		$idsp = array_keys($_SESSION['panierp']);
		if(empty($idsp)){
			$products = array();
		}else{
			$products = $this->DB->query('SELECT id, prix_vente,type FROM stock WHERE genre="plat" AND id IN ('.implode(',',$idsp).')');
		}
		foreach( $products as $productp ) {
			$totalp += $productp->prix_vente * $_SESSION['panierp'][$productp->id];
		}
		return $totalp;
	}

	public function totalb(){
		$totalb = 0;
		$idsb = array_keys($_SESSION['panierb']);
		if(empty($idsb)){
			$products = array();
		}else{
			$products = $this->DB->query('SELECT id, prix_vente FROM stock WHERE genre="boisson" AND id IN ('.implode(',',$idsb).')');
		}
		foreach( $products as $productb ) {
			$totalb += $productb->prix_vente * $_SESSION['panierb'][$productb->id];
		}
		return $totalb;
	}

	public function totald(){
		$totald = 0;
		$idsd = array_keys($_SESSION['panierd']);
		if(empty($idsd)){
			$products = array();
		}else{
			$products = $this->DB->query('SELECT id, prix_vente FROM stock WHERE genre="dessert" AND id IN ('.implode(',',$idsd).')');
		}
		foreach( $products as $productd ) {
			$totald += $productd->prix_vente * $_SESSION['panierd'][$productd->id];
		}
		return $totald;
	}

	public function totalc(){
		$totalc = 0;
		$idsd = array_keys($_SESSION['panierc']);
		if(empty($idsd)){
			$products = array();
		}else{
			$products = $this->DB->query('SELECT id, prix_vente FROM stock WHERE genre="cafe" AND id IN ('.implode(',',$idsd).')');
		}
		foreach( $products as $productc ) {
			$totalc += $productc->prix_vente * $_SESSION['panierc'][$productc->id];
		}
		return $totalc;
	}


	public function add($product_id){
		if(isset($_SESSION['panier'][$product_id])){
			$_SESSION['panier'][$product_id]++;
		}else{
			$_SESSION['panier'][$product_id] = 1;
		}
	}
	public function addbm($product_id){
		if(isset($_SESSION['paniermenub'][$product_id])){
			$_SESSION['paniermenub'][$product_id]++;
		}else{
			$_SESSION['paniermenub'][$product_id] = 1;
		}
	}
	public function adddm($product_id){
		if(isset($_SESSION['paniermenud'][$product_id])){
			$_SESSION['paniermenud'][$product_id]++;
		}else{
			$_SESSION['paniermenud'][$product_id] = 1;
		}
	}

	public function addcm($product_id){
		if(isset($_SESSION['paniermenuc'][$product_id])){
			$_SESSION['paniermenuc'][$product_id]++;
		}else{
			$_SESSION['paniermenuc'][$product_id] = 1;
		}
	}

	public function addb($product_id){
		if(isset($_SESSION['panierb'][$product_id])){
			$_SESSION['panierb'][$product_id]++;
		}else{
			$_SESSION['panierb'][$product_id] = 1;
		}
	}

	public function addd($product_id){
		if(isset($_SESSION['panierd'][$product_id])){
			$_SESSION['panierd'][$product_id]++;
		}else{
			$_SESSION['panierd'][$product_id] = 1;
		}
	}

	public function addc($product_id){
		if(isset($_SESSION['panierc'][$product_id])){
			$_SESSION['panierc'][$product_id]++;
		}else{
			$_SESSION['panierc'][$product_id] = 1;
		}
	}

	public function addp($product_id){
		if(isset($_SESSION['panierp'][$product_id])){
			$_SESSION['panierp'][$product_id]++;
		}else{
			$_SESSION['panierp'][$product_id] = 1;
		}
	}

	public function del($product_id){
		unset($_SESSION['panier'][$product_id]);
		unset($_SESSION['paniermenub'][$product_id]);
		unset($_SESSION['paniermenud'][$product_id]);
		unset($_SESSION['paniermenuc'][$product_id]);
		$_SESSION['error']=array(); //pour vider en cas d'erreur de payement
		unset($_SESSION['$quantite_rest']); //pour vider en cas de commande > au stock
	}

	public function delp($product_id){
		unset($_SESSION['panierp'][$product_id]);
		$_SESSION['error']=array(); //pour vider en cas d'erreur de payement
		unset($_SESSION['$quantite_rest']); //pour vider en cas de commande > au stock
	}

	public function delb($product_id){
		unset($_SESSION['panierb'][$product_id]);
		$_SESSION['error']=array(); //pour vider en cas d'erreur de payement
		unset($_SESSION['$quantite_rest']); //pour vider en cas de commande > au stock
	}

	public function deld($product_id){
		unset($_SESSION['panierd'][$product_id]);
		$_SESSION['error']=array(); //pour vider en cas d'erreur de payement
		unset($_SESSION['$quantite_rest']); //pour vider en cas de commande > au stock
	}


	public function delc($product_id){
		unset($_SESSION['panierc'][$product_id]);
		$_SESSION['error']=array(); //pour vider en cas d'erreur de payement
		unset($_SESSION['$quantite_rest']); //pour vider en cas de commande > au stock
	}

	// fonction cloturer la caisse
	public function totalsaisie(){
		
		$totalsaisie=0;

		$_SESSION['date']=date("y-m-d");
		$datea=substr($_SESSION['date'], 0, 4);
        $datem=substr($_SESSION['date'], 5, 2);
        $datej=substr($_SESSION['date'], 8, 2);

		$products = $this->DB->query('SELECT * FROM cloture WHERE DAY(date_cloture)= :jour And MONTH(date_cloture) = :mois AND YEAR(date_cloture) = :annee', array('jour' => date("d"), 'mois' => date("m"), 'annee' => date("20y") ));

		foreach( $products as $cloture ) {

			$totalsaisie = $cloture->tot_saisie ;

		}

		return $totalsaisie;
		
	}

	public function manque(){
		
		$manque=0;


		$products = $this->DB->query('SELECT SUM(difference) AS diff FROM cloture');

		foreach( $products as $cloture ) {

			$manque = $cloture->diff;

		}

		return $manque;
		
	}

	public function tableResto(){	
		$prod = $this->DB->query("SELECT *FROM tableresto order by(nom) ");
		
		return $prod;
	}

	public function nomTable($id){	
		$prod = $this->DB->querys("SELECT *FROM tableresto where id='{$id}'");
		
		return array(ucfirst($prod['nom']));
	}


	public function caisseJour($banque, $date1, $date2){

		if (isset($_POST['magasin']) and $_POST['magasin']=='general') {

			$prod=$this->DB->querys("SELECT sum(montant) as montant FROM banque where id_banque='{$banque}' and DATE_FORMAT(date_versement, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_versement, \"%Y%m%d\") <= '{$date2}'");

		}elseif (isset($_POST['magasin']) and $_POST['magasin']!='general') {

			$prod=$this->DB->querys("SELECT sum(montant) as montant FROM banque where id_banque='{$banque}' and DATE_FORMAT(date_versement, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_versement, \"%Y%m%d\") <= '{$date2}' ");
		}else{

			$prod=$this->DB->querys("SELECT sum(montant) as montant FROM banque where id_banque='{$banque}' and DATE_FORMAT(date_versement, \"%Y%m%d\") >='{$date1}' and DATE_FORMAT(date_versement, \"%Y%m%d\") <= '{$date2}' ");

		}

		return $prod['montant'];
		

	}

	#**************GESTION DE LA LICENCE*****************************

		public function licence(){
			
			$licence="";

			$products = $this->DB->query('SELECT num_licence, DATE_FORMAT(date_souscription, \'%d/%m/%Y\') AS debut, DATE_FORMAT(date_fin, \'%d/%m/%Y\') AS datefin, date_fin AS fin FROM licence');

	        foreach ( $products as $product ):?>

	       	<?php endforeach; ?>

	       	<?php
	       	$now = date('Y-m-d');
	       	$datefin = $product->fin;

	       	$now = new DateTime( $now );
	       	$now = $now->format('Ymd');
	       	$datefin = new DateTime( $datefin );
	       	$datefin = $datefin->format('Ymd');

	       	if ($now >= $datefin) {

	       		$licence="expiree";

	       	}else{

	       		$licence="ok";
	       	}

			return $licence;
			
		}

	

	// Sauvegarde de la base de donnée 
	public function dumpMySQL($serveur, $login, $password, $base, $mode){
	    $connexion = mysql_connect($serveur, $login, $password);
	    mysql_select_db($base, $connexion);
	 
	    $entete = "-- ----------------------\n";
	    $entete .= "-- dump de la base ".$base." au ".date("d-M-Y")."\n";
	    $entete .= "-- ----------------------\n\n\n";
	    $creations = "";
	    $insertions = "\n\n";
	 
	    $listeTables = mysql_query("show tables", $connexion);
	    while($table = mysql_fetch_array($listeTables))
	    {
	        // si l'utilisateur a demandé la structure ou la totale
	        if($mode == 1 || $mode == 3)
	        {
	            
	            $listeCreationsTables = mysql_query("show create table ".$table[0], $connexion);
	            while($creationTable = mysql_fetch_array($listeCreationsTables))
	            {
	              $creations .= $creationTable[1].";\n\n";
	            }
	        }
	        // si l'utilisateur a demandé les données ou la totale
	        if($mode > 1)
	        {
	            $donnees = mysql_query("SELECT * FROM ".$table[0]);
	            while($nuplet = mysql_fetch_array($donnees))
	            {
	                $insertions .= "INSERT INTO ".$table[0]." VALUES(";
	                for($i=0; $i < mysql_num_fields($donnees); $i++)
	                {
	                  if($i != 0)
	                     $insertions .=  ", ";
	                  if(mysql_field_type($donnees, $i) == "string" OR mysql_field_type($donnees, $i) == "datetime" OR mysql_field_type($donnees, $i) == "date" || mysql_field_type($donnees, $i) == "blob")
	                     $insertions .=  "'";
	                  $insertions .= addslashes($nuplet[$i]);
	                  if(mysql_field_type($donnees, $i) == "string" OR mysql_field_type($donnees, $i) == "datetime" OR mysql_field_type($donnees, $i) == "date" || mysql_field_type($donnees, $i) == "blob")
	                    $insertions .=  "'";
	                }
	                $insertions .=  ");\n";
	            }
	            $insertions .= "\n";
	        }
	    }
	 
	    mysql_close($connexion);
	 
	    $fichierDump = fopen("export.sql", "wb");
	    fwrite($fichierDump, $entete);
	    fwrite($fichierDump, $creations);
	    fwrite($fichierDump, $insertions);
	    fclose($fichierDump);
	}

}
