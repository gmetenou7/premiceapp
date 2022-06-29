<!-- begin::Aside Secondary -->
<div class="kt-aside__secondary">
	<div class="kt-aside__secondary-top">
		<h3 class="kt-aside__secondary-title">
			ELEMENTS
		</h3>
	</div>
	<!-- To hide the secondary menu remove "kt-aside--secondary-enabled" class from the body tag and remove below "kt-aside__secondary-bottom" block-->
	<div class="kt-aside__secondary-bottom">
		<!-- begin:: Aside Menu -->
		<div id="kt_aside_menu" class="kt-aside-menu  aziko" data-ktmenu-vertical="1" data-ktmenu-scroll="1">	
			<ul class="kt-menu__nav">
			    <?php if (session('users')['nom_ag'] == "" && session('users')['nom_serv'] == ""): ?>
			        <li class="kt-menu__section kt-menu__section--first"> 
                        <h4 class="kt-menu__section-text">Menu</h4> 
                        <i class="kt-menu__section-icon flaticon-more-v2"></i> 
                    </li>
                    <li class="kt-menu__item  kt-menu__item--active" aria-haspopup="true" >
                        <a  href="<?php echo base_url('home'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Tableau de bord</span></a>
                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                        <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                        <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">Apps</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                    <span class="kt-menu__link"><span class="kt-menu__link-text">Apps</span></span>
                                </li>
                                <li class="kt-menu__item" aria-haspopup="true">
                                    <a  href="<?php echo base_url('new_user'); ?>" id="new_user" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Nouvel Utilisateur</span>
                                    </a>
                                </li>
                                <?php if(!empty(session('users')['matricule_emp'])){?>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('profil/'.session('users')['matricule_emp']); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Mon profil</span>
                                        </a>
                                    </li>
                                <?php }else if(!empty(session('users')['matricule'])){ ?>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('profil/'.session('users')['matricule']); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Mon profil</span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                        <i class="kt-menu__section-icon flaticon-more-v2"></i>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Service Digital</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Service Digital</span></span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('inbox');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Inbox</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Stock</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Stock</span></span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('family');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Famille Produit</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('provider');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Fournisseur</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('article');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Article</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('depot');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Depot</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('stock');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entré Stock</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('mouvement');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Mouvement entré/Sorti</span></a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('transfert');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Transfert Stock</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('inventaire');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Inventaire</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a  href="<?php echo base_url('alstock');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Alert Stock</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a  href="<?php echo base_url('suivi_article');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">suivi article</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">SAV</span>
                                <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--brand">2</span></span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">SAV</span>
                                            <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--brand">2</span></span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('maintenance');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Maintenance</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Comptabilite</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Comptabilite</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('selsemp');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Vente Emp</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('custompurchase');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Achat Client</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('alertinvoice');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Alert Facture</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('annonce');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Annonces</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Commerce</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Commerce</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('facture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Règlement Ticket(TK)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rfacture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Reglement Client(RC)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('proformat'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Proformat</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonlivraison'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de livraison</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonretour'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de retour</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('sorticaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Sorti de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('ratache'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Ratachement Caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('ratacheb'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Ratachement Banque</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('situationcaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rapportdette'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Rapport Dette</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('suivi_article_vente'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Suivi D'article</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                         <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Stat vente</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Produits tres vendu</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/charts.html" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Conso client</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Points Client</span>
                                        </a>
                                    </li>

                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Clients Réguliers</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Vente</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">Configuration</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>

                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Compte</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Compte</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('message');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Configurer Messages</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('new_agence'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Nouvel/Liste agence</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('new_service'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau/Liste service</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('document'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Type/Liste documents</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('caisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Caisse/Liste Caisses</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('taxe'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text text-truncate">Taxe/Liste Taxes/heure clo</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('tpoint'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Point/Prix</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('inside'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Inside</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
			    <?php endif; ?>
			    <?php if (session('users')['nom_ag'] != "" && session('users')['nom_serv'] == ""): ?>
			        <li class="kt-menu__section kt-menu__section--first"> 
                        <h4 class="kt-menu__section-text">Menu</h4> 
                        <i class="kt-menu__section-icon flaticon-more-v2"></i> 
                    </li>
                    <li class="kt-menu__item  kt-menu__item--active" aria-haspopup="true" >
                        <a  href="<?php echo base_url('home'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Tableau de bord</span></a>
                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                        <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                        <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">Apps</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                    <span class="kt-menu__link"><span class="kt-menu__link-text">Apps</span></span>
                                </li>
                                <li class="kt-menu__item" aria-haspopup="true">
                                    <a  href="<?php echo base_url('new_user'); ?>" id="new_user" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Nouvel Utilisateur</span>
                                    </a>
                                </li>

                                <?php if(!empty(session('users')['matricule_emp'])){?>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('profil/'.session('users')['matricule_emp']); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Mon profil</span>
                                        </a>
                                    </li>
                                <?php }else if(!empty(session('users')['matricule'])){ ?>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('profil/'.session('users')['matricule']); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Mon profil</span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                        <i class="kt-menu__section-icon flaticon-more-v2"></i>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                        <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">Service Digital</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu ">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                    <span class="kt-menu__link"><span class="kt-menu__link-text">Service Digital</span></span>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true" >
                                    <a  href="<?php echo base_url('inbox');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Inbox</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                        <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">Stock</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu ">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                    <span class="kt-menu__link"><span class="kt-menu__link-text">Stock</span></span>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true" >
                                    <a  href="<?php echo base_url('family');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Famille Produit</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true" >
                                    <a  href="<?php echo base_url('provider');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Fournisseur</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true" >
                                    <a  href="<?php echo base_url('article');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Article</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true" >
                                    <a  href="<?php echo base_url('depot');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Depot</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true" >
                                    <a  href="<?php echo base_url('stock');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Entré Stock</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true" >
                                    <a  href="<?php echo base_url('mouvement');?>" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">Mouvement entré/Sorti</span></a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true" >
                                    <a  href="<?php echo base_url('transfert');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Transfert Stock</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true" >
                                    <a  href="<?php echo base_url('inventaire');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Inventaire</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a  href="<?php echo base_url('alstock');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Alert Stock</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a  href="<?php echo base_url('suivi_article');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">suivi article</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                        <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">SAV</span>
                            <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--brand">2</span></span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu ">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                    <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">SAV</span>
                                        <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--brand">2</span></span>
                                    </span>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('maintenance');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Maintenance</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">Comptabilite</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu ">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                    <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">Comptabilite</span>
                                    </span>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('selsemp');?>" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">Vente Emp</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('custompurchase');?>" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">Achat Client</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('alertinvoice');?>" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">Alert Facture</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('annonce');?>" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">Annonces</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">Commerce</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu ">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                    <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">Commerce</span>
                                    </span>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('facture'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Règlement Ticket(TK)</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('rfacture'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Reglement Client(RC)</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('proformat'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Proformat</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('bonlivraison'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Bon de livraison</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('bonretour'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Bon de retour</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('sorticaisse'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Sorti de caisse</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Entrer de caisse</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('ratache'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Ratachement Caisse</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('ratacheb'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Ratachement Banque</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('situationcaisse'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Situation De Caisse</span>
                                    </a>
                                </li>
                                <!--<li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php //echo base_url('situationbanque'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Situation De Banque</span>
                                    </a>
                                </li>-->
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('rapportdette'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Rapport Dette</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('suivi_article_vente'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Suivi D'article</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                     <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">AUTRES</h4>
                        <i class="kt-menu__section-icon flaticon-more-v2"></i>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">Clients</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu ">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                    <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">Clients</span>
                                    </span>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Nouveau Client</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-text">Stat vente</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu ">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                    <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">Produits tres vendu</span>
                                    </span>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="components/widgets/charts.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Conso client</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="components/widgets/general.html" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">Points Client</span>
                                    </a>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="components/widgets/general.html" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">Clients Réguliers</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="components/widgets/general.html" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">Vente</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
			    <?php endif; ?>
			    <?php if (session('users')['nom_ag'] != "" && session('users')['nom_serv'] != ""): ?>
    			    <?php if (session('users')['nom_serv'] == "COMPTABILITE"): ?>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Comptabilite</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Comptabilite</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('selsemp');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Vente Emp</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('custompurchase');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Achat Client</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('alertinvoice');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Alert Facture</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('annonce');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Annonces</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Commerce</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Commerce</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('facture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Règlement Ticket(TK)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rfacture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Reglement Client(RC)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('proformat'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Proformat</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonlivraison'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de livraison</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonretour'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de retour</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('sorticaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Sorti de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('ratache'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Ratachement Caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('ratacheb'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Ratachement Banque</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('situationcaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Caisse</span>
                                        </a>
                                    </li>
                                    <!--<li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('situationbanque'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Banque</span>
                                        </a>
                                    </li>-->
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rapportdette'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Rapport Dette</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                    <a href="<?php echo base_url('suivi_article_vente'); ?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Suivi D'article</span>
                                    </a>
                                </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Stat vente</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Produits tres vendu</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/charts.html" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Conso client</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Points Client</span>
                                        </a>
                                    </li>
    
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Clients Réguliers</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Vente</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			    <?php if (session('users')['nom_serv'] == "SAV"): ?>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">SAV</span>
                                <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--brand">2</span></span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">SAV</span>
                                            <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--brand">2</span></span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('maintenance');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Maintenance</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			    <?php if (session('users')['nom_serv'] == "SD"): ?>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Service Digital</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Service Digital</span></span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('inbox');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Inbox</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Stat vente</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Produits tres vendu</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/charts.html" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Conso client</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Points Client</span>
                                        </a>
                                    </li>
    
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Clients Réguliers</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Vente</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			    <?php if (session('users')['nom_serv'] == "STOCK"): ?>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Stock</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Stock</span></span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('mouvement');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Mouvement entré/Sorti</span></a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('transfert');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Transfert Stock</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('inventaire');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Inventaire</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a  href="<?php echo base_url('alstock');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Alert Stock</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a  href="<?php echo base_url('suivi_article');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">suivi article</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Commerce</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Commerce</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('facture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Règlement Ticket(TK)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rfacture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Reglement Client(RC)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('proformat'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Proformat</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonlivraison'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de livraison</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('situationcaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('suivi_article_vente'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Suivi D'article</span>
                                        </a>
                                    </li>
                                    <!--<li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('bonretour'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de retour</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('sorticaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Sorti de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('ratache'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Ratachement</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('situationcaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('situationbanque'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Banque</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('rapportdette'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Rapport Dette</span>
                                        </a>
                                    </li>-->
                                </ul>
                            </div>
                        </li>
                        
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			    <?php if (session('users')['nom_serv'] == "COMMERCIAL"): ?>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Commerce</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Commerce</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('facture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Règlement Ticket(TK)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rfacture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Reglement Client(RC)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('proformat'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Proformat</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonlivraison'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de livraison</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('suivi_article_vente'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Suivi D'article</span>
                                        </a>
                                    </li>
                                    <!--<li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('bonretour'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de retour</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('sorticaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Sorti de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('ratache'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Ratachement</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('situationcaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('situationbanque'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Banque</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('rapportdette'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Rapport Dette</span>
                                        </a>
                                    </li>-->
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			    <?php if (session('users')['nom_serv'] == "CAISSE"): ?>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Commerce</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Commerce</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('facture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Règlement Ticket(TK)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rfacture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Reglement Client(RC)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('proformat'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Proformat</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonlivraison'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de livraison</span>
                                        </a>
                                    </li>
                                    <!--<li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('bonretour'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de retour</span>
                                        </a>
                                    </li>-->
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('sorticaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Sorti de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <!--<li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('ratache'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Ratachement</span>
                                        </a>
                                    </li>-->
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('situationcaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('suivi_article_vente'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Suivi D'article</span>
                                        </a>
                                    </li>
                                    <!--<li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('situationbanque'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Banque</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('rapportdette'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Rapport Dette</span>
                                        </a>
                                    </li>-->
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			<?php endif; ?>
    			<?php if (session('users')['nom_ag'] == "" && session('users')['nom_serv'] != ""): ?>
    			    <?php if (session('users')['nom_serv'] == "COMPTABILITE"): ?>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Apps</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Apps</span></span>
                                    </li>
                                    <li class="kt-menu__item" aria-haspopup="true">
                                        <a  href="<?php echo base_url('new_user'); ?>" id="new_user" class="kt-menu__link">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouvel Utilisateur</span>
                                        </a>
                                    </li>
    
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('profil/'.session('users')['matricule_emp']); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Mon profil</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Comptabilite</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Comptabilite</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('selsemp');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Vente Emp</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('custompurchase');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Achat Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Commerce</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Commerce</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('facture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Règlement Ticket(TK)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rfacture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Reglement Client(RC)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('proformat'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Proformat</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonlivraison'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de livraison</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonretour'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de retour</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('sorticaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Sorti de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('ratache'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Ratachement</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('situationcaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('situationbanque'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Banque</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rapportdette'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Rapport Dette</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('suivi_article_vente'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Suivi D'article</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Stat vente</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Produits tres vendu</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/charts.html" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Conso client</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Points Client</span>
                                        </a>
                                    </li>
    
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Clients Réguliers</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Vente</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			    <?php if (session('users')['nom_serv'] == "SAV"): ?>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Apps</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Apps</span></span>
                                    </li>
                                    <li class="kt-menu__item" aria-haspopup="true">
                                        <a  href="<?php echo base_url('new_user'); ?>" id="new_user" class="kt-menu__link">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouvel Utilisateur</span>
                                        </a>
                                    </li>
    
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('profil/'.session('users')['matricule_emp']); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Mon profil</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">SAV</span>
                                <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--brand">2</span></span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">SAV</span>
                                            <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--brand">2</span></span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('maintenance');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Maintenance</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			    <?php if (session('users')['nom_serv'] == "SD"): ?>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Apps</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Apps</span></span>
                                    </li>
                                    <li class="kt-menu__item" aria-haspopup="true">
                                        <a  href="<?php echo base_url('new_user'); ?>" id="new_user" class="kt-menu__link">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouvel Utilisateur</span>
                                        </a>
                                    </li>
    
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('profil/'.session('users')['matricule_emp']); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Mon profil</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Service Digital</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Service Digital</span></span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('inbox');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Inbox</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Stat vente</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Produits tres vendu</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/charts.html" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Conso client</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Points Client</span>
                                        </a>
                                    </li>
    
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Clients Réguliers</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/widgets/general.html" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Vente</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			    <?php if (session('users')['nom_serv'] == "STOCK"): ?>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Apps</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Apps</span></span>
                                    </li>
                                    <li class="kt-menu__item" aria-haspopup="true">
                                        <a  href="<?php echo base_url('new_user'); ?>" id="new_user" class="kt-menu__link">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouvel Utilisateur</span>
                                        </a>
                                    </li>
    
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('profil/'.session('users')['matricule_emp']); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Mon profil</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Stock</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Stock</span></span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('family');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Famille Produit</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('provider');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Fournisseur</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('article');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Article</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('depot');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Depot</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('stock');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entré Stock</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('mouvement');?>" class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">Mouvement entré/Sorti</span></a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('transfert');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Transfert Stock</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('inventaire');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Inventaire</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a  href="<?php echo base_url('alstock');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Alert Stock</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a  href="<?php echo base_url('suivi_article');?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">suivi article</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			    <?php if (session('users')['nom_serv'] == "COMMERCIAL"): ?>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                            <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Apps</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" >
                                        <span class="kt-menu__link"><span class="kt-menu__link-text">Apps</span></span>
                                    </li>
                                    <li class="kt-menu__item" aria-haspopup="true">
                                        <a  href="<?php echo base_url('new_user'); ?>" id="new_user" class="kt-menu__link">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouvel Utilisateur</span>
                                        </a>
                                    </li>
    
                                    <li class="kt-menu__item " aria-haspopup="true" >
                                        <a  href="<?php echo base_url('profil/'.session('users')['matricule_emp']); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Mon profil</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Commerce</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Commerce</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('facture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Règlement Ticket(TK)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rfacture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Reglement Client(RC)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('proformat'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Proformat</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonlivraison'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de livraison</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('suivi_article_vente'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Suivi D'article</span>
                                        </a>
                                    </li>
                                    <!--<li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('bonretour'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de retour</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('sorticaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Sorti de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('ratache'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Ratachement</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('situationcaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('situationbanque'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Banque</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('rapportdette'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Rapport Dette</span>
                                        </a>
                                    </li>-->
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			    <?php if (session('users')['nom_serv'] == "CAISSE"): ?>
    			        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" >
                            <a  href="<?php echo base_url('marchandise'); ?>" class="kt-menu__link "><span class="kt-menu__link-text">Articles</span></a>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">COMPOSANTES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Commerce</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Commerce</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('facture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Règlement Ticket(TK)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('rfacture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Reglement Client(RC)</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('proformat'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Proformat</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('bonlivraison'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de livraison</span>
                                        </a>
                                    </li>
                                    <!--<li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('bonretour'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bon de retour</span>
                                        </a>
                                    </li>-->
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('sorticaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Sorti de caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('entrecaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Entrer de caisse</span>
                                        </a>
                                    </li>
                                    <!--<li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('ratache'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Ratachement</span>
                                        </a>
                                    </li>-->
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('situationcaisse'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Caisse</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('suivi_article_vente'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Suivi D'article</span>
                                        </a>
                                    </li>
                                    <!--<li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('situationbanque'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Situation De Banque</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php //echo base_url('rapportdette'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Rapport Dette</span>
                                        </a>
                                    </li>-->
                                </ul>
                            </div>
                        </li>
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">AUTRES</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-text">Clients</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Clients</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="<?php echo base_url('costomers'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nouveau Client</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
    			    <?php endif; ?>
    			<?php endif; ?>
            </ul>
		</div>
		<!-- end:: Aside Menu -->
	</div>
</div>
<!-- end::Aside Secondary -->	




