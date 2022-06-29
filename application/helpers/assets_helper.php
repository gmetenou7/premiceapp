<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');

        /**gestion des url des fichiers assets */
    if(!function_exists('assets_dir')){
        function assets_dir(){
            return base_url() . 'assets/';
        }
    }

    /**type doc detect */
    function typedoc($code_document){
        return substr($code_document, 0, 2);
    }

    /**formater la date */
    function  dateformat($date){
        $dateformat = !empty($date)?date('d-m-Y H:i:s', strtotime($date)):'';
        return $dateformat;
    }

    /**calcule le temps restant entre deux heur */
    function timechrono($heurdb,$confir=false){
        $nows = date('H:i:s');
        $h1=strtotime($heurdb);
        $h2=strtotime($nows);
        $h4 = ($h1-$h2);
        if($confir){
            return date("s",strtotime("-1 hours",$h4));
        }else{
            return date("H:i:s",strtotime("-1 hours",$h4));
        }
    }

    /**formater un nombre */
    function numberformat($nbr){
        $nbrformat ="";
        if(!empty($nbr)){
            if($nbr == 0){
                $nbrformat .= $nbr;
            }else{
                $nbrformat .=  number_format($nbr,1,","," "); 
            }
        }
        return $nbrformat;
    }

    /**type doc detecte */
    function typedocdetect($codedocument){
        $typedocu = substr($codedocument, 0, 2);
        return $typedocu;
    }

    /**nombre de jours entre 2dates */
    function nbrjourligne($date1,$date2){
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        $diff = $date2->diff($date1)->format("%a");
        return $diff;
    }

    /** function for debug */
    function debug($variable){
        echo '<pre>'.print_r($variable,true).'</pre>';
    }

    /**permet de générer les codes comme: le matricule de l'utilisateur... */
    function code($longueur){
        $alphabet="ABU56789VWXYZEFCDSTGHIJKLMNOPQR12340";
        /**repete la chaine de caractère alphabet */
        return substr(date('Y'), -2, 2).substr(str_shuffle(str_repeat($alphabet, $longueur)), 0, $longueur);
    }

    /***génère un mot de passe par defaut pour un employé qu'on crée*/
    function pass($longueur){
        $alphabet="azertyuiop45@6789KLMsdfghjklmwxcvbYZ1230&";
        /**repete la chaine de caractère alphabet */
        return substr(str_shuffle(str_repeat($alphabet, $longueur)), 0, $longueur);
    }

    /**function qui return le dateTime */
    function dates(){
        date_default_timezone_set("Africa/Douala");
        return date("Y-m-d H:i:sa");
    }

     /**debut gestion des sessions */
     if(!function_exists("session")){
        /**
         * @param $var
         * @param null $value
         * @return mixed
         */
        function session($var, $value = null){
            if(is_string($var) && $value === null){
                return CI_Controller::get_instance()->session->userdata($var);
            }
    
            if(is_string($var) && $value !== null){
                CI_Controller::get_instance()->session->set_userdata($var, $value);
            }
    
            if(is_array($var)){
                CI_Controller::get_instance()->session->set_userdata($var);
            }
        }
    }
    
    if(!function_exists("un_sess")){
        /**
         * @param $var
         * @param null $value
         * @return mixed
         */
        function un_sess($var){
            return CI_Controller::get_instance()->session->unset_userdata($var);
        }
    }
    
    if(!function_exists('session_destroy')){
        /**
         * @param $var
         * @param null $value
         * @return mixed
         */
        function session_destroy($var){
            return CI_Controller::get_instance()->session->sess_destroy($var);
        }
    }
    
    /**fin gestion des sessions */


    /**message flash debut */
if(!function_exists("flash")){
    /**
     * @param array|string $data
     * @param null $value
     * @return mixed
     */
    function flash($data, $value = null){

        if(!is_array($data) && $value){
            CI_Controller::get_instance()->session->set_flashdata($data, $value);
             return true;
        }

        if(is_array($data)){
            CI_Controller::get_instance()->session->set_flashdata($data);
            return true;
        }


        if(!is_array($data) && is_null($value)){
            return CI_Controller::get_instance()->session->flashdata($data);
        }

    }
}
 /**message flash fin */



 /*debut carte de fidélité*/
    function carte_fidelite($matricule_cli){

      $code = $matricule_cli;

      /* *****************************************
      * exemple d'utilisation de pi_barcode.php
      * par pitoo.com
      * ***************************************** */
      //$this->load->view('barcode/pi_barcode');
      include('assets/barcode/pi_barcode.php');
      
      // instanciation
      $bc = new pi_barcode();
      
      // Le code a générer
      $bc->setCode($code);
      // Type de code : EAN, UPC, C39...
      $bc->setType('C128');
      // taille de l'image (hauteur, largeur, zone calme)
      //    Hauteur mini=15px
      //    Largeur de l'image (ne peut être inférieure a
      //        l'espace nécessaire au code barres
      //    Zones Calmes (mini=10px) à gauche et à droite
      //        des barres
      $bc->setSize(50, 0, 10);
      
      // Texte sous les barres :
      //    'AUTO' : affiche la valeur du codes barres
      //    '' : n'affiche pas de texte sous le code
      //    'texte a afficher' : affiche un texte libre
      //        sous les barres
      $bc->setText('AUTO');
      
      // Si elle est appelée, cette méthode désactive
      // l'impression du Type de code (EAN, C128...)
      $bc->hideCodeType();
      
      // Couleurs des Barres, et du Fond au
      // format '#rrggbb'
      $bc->setColors('#000000', '#FFFFFF');
      // Type de fichier : GIF ou PNG (par défaut)
      $bc->setFiletype('PNG');
        
      // envoie l'image dans un dossier
      $bc->writeBarcodeFile('assets/barcode/barcode_generer/barcode_'.$code.'.png');
      // ou envoie l'image au navigateur
      // $bc->showBarcodeImage();
      
      /* ***************************************** */

      /**
     * augmenté la taille de l'image
     * $x largeur a redimensionner
     * 
     * $y hauteur a redimensionner
     */

        $chemin = 'assets/barcode/barcode_generer/barcode_'.$code.'.png';
        $x=700;
        $y=100;
        $img_new = imagecreatefrompng($chemin);
        $size = getimagesize($chemin);
        $img_mini = imagecreatetruecolor ($x, $y);
        imagecopyresampled ($img_mini,$img_new,0,0,0,0,$x,$y,$size[0],$size[1]);
        imagepng($img_mini, 'assets/barcode/barcode_generer/barcode_mini_'.$code.'.png');

        /*superposition d'image (le code à bare sur l'image de la carte de visite ce qui donne la carte de fidélité final*/

        // On charge d'abord les images
        $source = imagecreatefrompng('assets/barcode/barcode_generer/barcode_mini_'.$code.'.png'); // Le logo est la source
        $destination = imagecreatefromjpeg('assets/barcode/cartefidelite/pasTouche/original.jpg'); // La photo est la destination

        // Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
        $largeur_source = imagesx($source);
        $hauteur_source = imagesy($source);
        $largeur_destination = imagesx($destination);
        $hauteur_destination = imagesy($destination);
        // On veut placer le logo en bas à droite, on calcule les coordonnées où on doit placer le logo sur la photo
        $destination_x = 200;  //$largeur_destination - $largeur_source;
        $destination_y = 100;  //$hauteur_destination - $hauteur_source;

        // On met le logo (source) dans l'image de destination (la photo)
        imagecopymerge($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source, 100);

        // On affiche l'image de destination qui a été fusionnée avec le logo
        imagejpeg($destination, 'assets/barcode/cartefidelite/carte_fidelite.jpg');


        /*afficher les cartes avec les nom des clients dans le dossier cartefidelite_a_imprimer*/
         // On charge d'abord les images
        $source = imagecreatefrompng('assets/barcode/barcode_generer/barcode_mini_'.$code.'.png'); // Le logo est la source
        $destination = imagecreatefromjpeg('assets/barcode/cartefidelite/pasTouche/original.jpg'); // La photo est la destination

        // Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
        $largeur_source = imagesx($source);
        $hauteur_source = imagesy($source);
        $largeur_destination = imagesx($destination);
        $hauteur_destination = imagesy($destination);
        // On veut placer le logo en bas à droite, on calcule les coordonnées où on doit placer le logo sur la photo
        $destination_x = 160;  //$largeur_destination - $largeur_source;
        $destination_y = 100;  //$hauteur_destination - $hauteur_source;

        // On met le logo (source) dans l'image de destination (la photo)
        imagecopymerge($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source, 100);

        // On affiche l'image de destination qui a été fusionnée avec le logo
        imagejpeg($destination, 'assets/barcode/cartefidelite_a_imprimer/carte_fidelite_'.$code.'.jpg');
    }
    /*fin carte de fidélité*/



    /** function  qui génère les jours */
    function jour(){
        $i=1;
        for($i; $i<32; $i++){
            if($i<=9){
                $i = '0'.$i;
            }
            $jour[] = $i;
        }
        return $jour;
    }

    /** function  qui génère les annee */
    function annee(){
        $i=1950;
        $limite = date('Y')-11;
        for($i; $i<$limite; $i++){
            $annee[] = $i;
        }
        return $annee;
    }

    /** function  qui génère les mois */
    function mois(){
        $mois_lettre = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
        $i=0;
        foreach($mois_lettre as $row){
            $i+=1;
            if($i<=9){
                $i = '0'.$i;
            }
            $mois[$i] = $row;
        }
        return $mois;
    }



    /**générer une le rapport pdf de mintenance*/
    function rapport_maintenance($query){
        try{
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
            //$mpdf->SetJS('this.print();');
            $imageh = assets_dir().'media/baniere/banier_header.jpeg';
            $imagef = assets_dir().'media/baniere/banier_footer.jpeg';

            $mpdf->SetHTMLHeader('
            <div style="text-align: center; font-weight: bold;">
                <u>RAPPORT DE MAINATENCE PREMICE COMPUTER</u>
            </div>');

            $mpdf->SetHTMLFooter('
            <table width="100%">
                <tr>
                    <td width="33%">{DATE j-m-Y}</td>
                    <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right;">Rapport de maintenance</td>
                </tr>
            </table>');

    
        $mpdf->WriteHTML('
                
                <hr>
                <table style="border-collapse: collapse; border: 1px solid black;">
                    <tr>
                        <th>Code<th>
                        <th>Nom<th>
                        <th>Numéro de serie<th>
                        <th>Référence<th>
                        <th>Description Initiale<th>
                        <th>Diagnostique<th>
                        <th>Prix du Diagnostique<th>
                        <th>Date du Diagnostique<th>
                        <th>Problème à résoudre<th>
                        <th>Prix de maintenance<th>
                        <th>Statut actuel<th>
                        <th>Date d\'enrégistrement<th>
                        <th>Enregistrer par<th>
                        <th>Equipement de<th>
                        <th>Prix Total du diagnostique et de la maintenance<th>
                    </tr>');
                    if($query){
                        foreach ($query as $key => $value) {
                        $mpdf->WriteHTML('
                            <tr style="text-align: center;">
                                <td>'.$value['code_reparation'].'<td>
                                <td>'.$value['nom_equip'].'<td>
                                <td>'.$value['numero_serie_equip'].'<td>
                                <td>'.$value['reference_equip'].'<td>
                                <td>'.$value['description_equip'].'<td>
                                
                                <td>'.$value['diagnostique'].'<td>
                                <td>'.$value['prix_diagnostique'].'<td>
                                <td>'.$value['date_diagnostique'].'<td>
    
                                <td>'.$value['description_reparation'].'<td>
                                <td>'.$value['prix_reparation'].'<td>
                                <td>'.$value['statut_reparation'].'<td>
                                <td>'.$value['date_creer_reparation'].'<td>
                                <td>'.$value['nom_emp'].'<td>
                                <td>'.$value['nom_cli'].'<td>
                                <td>'.($value['prix_diagnostique']+$value['prix_reparation']).'<td>
                            </tr>');
                        }
                    }

            $mpdf->WriteHTML('		
                </table>
            ');

            $mpdf->Output('rapport_maintenance.pdf','I');
        /*************** */
        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            echo $e->getMessage();
        }
    }

    