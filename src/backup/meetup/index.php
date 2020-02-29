<?
session_start();

function email($mail, $predmet, $odesilatel, $odes_mail, $text)
{
      $sub1 = base64_encode ($predmet);
      $subject = "=?utf-8?B?".$sub1."?=";
      $from1 = base64_encode ($odesilatel);
      $from = "=?utf-8?B?".$from1."?=";
      $encoding = "8bit";
      $charset = "text/html; charset=\"utf-8\"";
      $hlavicky = "From: ".$from." <".$odes_mail.">\n";
      $hlavicky .= "Content-Transfer-Encoding:" . $encoding . "\n";
      $hlavicky .= "Content-Type: " . $charset;
      mail($mail,$subject,$text, $hlavicky);
}

if(isset($_POST['buy']))
{
	$vsfile=fopen("vs.txt", "r");
	$vs = intval(fread($vsfile, filesize("vs.txt"))) + 1;
	$vsfile=fopen("vs.txt", "w");
	fwrite($vsfile, $vs);
	fclose($vsfile);

	if($_POST['company']) $jmeno = $_POST['company'];
	else $jmeno = $_POST['jmeno'];
  
  $actualDate = new DateTime();
  $actualDatePlusSeven = date('d.m.Y', strtotime($actualDate->format('Y-m-d H:i:s') . ' +7 day'));
	$text = 'Dobrý den,<br><br>děkujeme za nákup vstupenky na Czech HR MeetUp.<br><br>Uhraďte prosím částku <b>'.($_POST['qty'] * 6655).' Kč</b> na účet číslo <b>6657891001/5500</b>. Jako variabilní symbol zadejte <b>'.date("Y").$vs.'</b>.<br><br>
	<br><b style="font-size:18px;">Zálohová faktura č. '.date("Y").$vs.'</b>
	<table style="border-collapse:collapse; width:100%;">
		<tr>
			<td style="border: 1px solid #000; padding:5px;">
				<b>Dodavatel:</b><br>
				BeeMedia, s.r.o.<br>
				Janáčkovo nábřeží 86/7<br>
				150 00, Praha 5<br>
				<br>
				<b>IČ:</b> 24810860<br>
				<b>DIČ:</b> CZ24810860<br>
			</td>
			<td style="border: 1px solid #000; padding:5px;">
				<b>Odběratel:</b><br>
				'.$jmeno.'<br>
				'.$_POST['street'].'<br>
				'.$_POST['zip'].', '.$_POST['city'].'<br>
				<br>
				<b>IČ:</b> '.$_POST['ic'].'<br>
				<b>DIČ:</b> '.$_POST['dic'].'<br>
			</td>
		</tr>
		<tr>
			<td style="border: 1px solid #000; padding:5px;">
				<b>Způsob platby:</b> Bankovní převod<br>
				<b>Variabilní symbol:</b> '.date("Y").$vs.'<br>
				<b>Bankovní účet:</b> 6657891001/5500
			</td>
			<td style="border: 1px solid #000; padding:5px;">
				<b>Datum vystavení:</b> '.date('j. n. Y').'<br>
				<b>Datum zdanitelného plnění:</b> '.date('j. n. Y').'<br>
				<b>Datum splatnosti:</b> '.$actualDatePlusSeven.'
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border: 1px solid #000; padding:5px;">

				<table style="border-collapse:collapse; width:100%;">
					<tr>
						<td><b>Položka</b></td>
						<td align="right"><b>Počet</b></td>
						<td align="right"><b>Cena</b></td>
						<td align="right"><b>DPH</b></td>
						<td align="right"><b>Cena celkem</b></td>
					</tr>
					<tr>
						<td>Vstupenka - Czech HR MeetUp</td>
						<td align="right">1 ks</td>
						<td align="right">'.round( ($_POST['qty'] * 6655) / 1.21, 2 ).' Kč</td>
						<td align="right">21 %</td>
						<td align="right">'.($_POST['qty'] * 6655).' Kč</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right" style="border: 1px solid #000; padding:5px; font-size:18px;">
				Celková cena s DPH: <b>'.($_POST['qty'] * 6655).' Kč</b>
			</td>
		</tr>
	</table>
	<i>Toto není daňový doklad</i>

	<br><br>S přáním hezkého dne,<br>
	Tým Czech HR MeetUp<br>
	www.hrsummit.cz';
	email($_POST['email'], "Vstupenka - Czech HR MeetUp", 'Czech HR MeetUp', 'info@hrsummit.cz',$text);
	
	if($_POST['job']) $_POST['job'] = ' - ' . $_POST['job'];

	email('info@hrsummit.cz', "Vstupenka - Czech HR MeetUp", $_POST['name'], $_POST['email'],'<b>Právě byl proveden nový nákup vstupenky na webu hrsummit.cz od '.$_POST['name'] . $_POST['job'] .'.<br><br>KOPIE EMAILU ZASLANÉHO ZÁKAZNÍKOVI:<br><br></b>'.$text);

	$alert1 = 'Děkujeme za nákup vstupenky. Na vaši emailovou adresu jsme zaslali údaje k provedení platby.';
}

if(isset($_POST['send']))
{
	email('info@hrsummit.cz', "Zpráva z kontaktního formuláře hrsummit.cz", $_POST['name'], $_POST['email'],nl2br($_POST['message']));
	$alert2 = 'Zpráva byla úspěšně odeslána. Budeme se jí věnovat co nejdříve.';

}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>Czech HR MeetUp</title>
	<link rel="shortcut icon" type="image/png" href="img/fav.png"/>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700" rel="stylesheet">
	<link href="main.css?v=3" rel="stylesheet">

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-96782855-1', 'auto');
	  ga('send', 'pageview');   

	</script>    
</head>
<body>            

<div id="black"></div>

<? if($alert1) { ?>
	<div id="alert">
		<a href="#" id="alert-close">×</a>
		<h1>Děkujeme za nákup vstupenky</h1>
		<p>Uhraďte prosím částku <b><?= $_POST['qty'] * 6655 ?> Kč</b> na účet číslo <b>6657891001/5500</b>. Jako variabilní symbol zadejte <b><?= date("Y") . $vs ?></b></p>
		<p>Údaje k platbě najdete také ve své emailové schránce.</p>
		<a href="#" id="alert-button" class="buy">Zavřít</a>
	</div>
<? } ?>

<? if($alert2) { ?>
	<div id="alert">
		<a href="#" id="alert-close">×</a>
		<h1>Zpráva byla odeslána</h1>
		<p>Vaše zpráva byl úspěšně odeslána. Budeme se jí věnovat co nejdříve.</p>
		<a href="#" id="alert-button" class="buy">Zavřít</a>
	</div>
<? } ?>

    <header id="main-header">
        <div class="webwidth">
            <nav class="main-nav">
                <ul>
                    <li><a href="#">Domů</a></li>
                    <li><a href="#o-hr-meetup">O HR MeetUp</a></li>
                    <li><a href="#prednasejici">Přednášející</a></li>
                    <li><a href="#kontakt">Kontakt</a></li>
                    <li><a href="#buy-ticket" class="green">Koupit vstupenku</a></li>
                </ul>
            </nav>
            <div id="mobile-links">
                <a href="#buy-ticket" id="mobile-ticket-link" class="scroll-to-href">
                    <span class="mobile-ticket-buy">Koupit vstupenku</span>
                    <span class="mobile-ticket-ticket">Vstupenka</span>
                </a>
                <a href="#" id="mobile-menu-link">Menu</a>
            </div>
            <a href="#" class="scroll-to-href"><img src="img/logo.svg" class="logo"></a>
        </div>
    </header>

    <section id="home">

        <div class="intro" id="intro">

            <div class="intro__top">

                <div class="webwidth">

                    <p class="intro__claim">
                        <span>Sdílení</span>
                        <span>nejlepších</span>
                        <span>praktik</span>
                        <span>v oblasti řízení</span>
                        <span>lidských</span>
                        <span>zdrojů</span>
                    </p>

                </div>
                <!-- /.webwidth -->

            </div>
            <!-- /.intro__top -->

            <div class="intro__bottom">

                <div class="webwidth">

                    <h1 class="intro__title">HR Meetup</h1>

                    <div class="intro__info">

                        <div class="intro__info-place">

                            <p>
                                <b>
                                    Podolská vodárna<br>
                                    Praha 4 - Podolí
                                </b>
                            </p>

                        </div>

                        <div class="intro__info-time">

                            <time datetime="2018-10-11T09:00">
                                <b>11. 10. 2018</b><br>
                                9.00 - 13.00 hodin
                            </time>

                        </div>

                    </div>
                    <!-- /.intro__info -->

                </div>
                <!-- /.webwidth -->

            </div>
            <!-- /.intro__bottom -->

        </div>

        <article class="about" id="o-hr-meetup">

            <div class="webwidth">

                <h1>Cíl akce</h1>

                <p>Tato akce bude volně navazovat na 2.ročník Czech HR Summit 2018, který se konal dne 15.5.2018 na téma „ Automatizace, digitalizace a robotizace “ a bude mít za úkol vysdílet od předních odborníků vybraná doporučení v oblasti řízení lidských zdrojů.</p>

                <h2>Cílová skupina:</h2>        

                <ul>

                    <li>personální ředitelé a manažeři</li>

                    <li>majitelé středních a menších společností</li>

                    <li>obchodní ředitelé</li> 

                </ul>

                <ul class="about__logos">

                    <li class="about__logo">
                        <a class="about__logo-link" href="http://www.veolia.cz/cs">
                            <img class="about__logo-img" src="img/about__logo--veolia.png" alt="Veolia - logo">
                        </a>
                    </li>

                    <li class="about__logo">
                        <a class="about__logo-link" href="https://www.czechinvest.org/cz">
                            <img class="about__logo-img" src="img/about__logo--czechinvest.png" alt="Czechinvest - logo">
                        </a>
                    </li>

                    <li class="about__logo">
                        <a class="about__logo-link" href="https://www.aramark.cz/">
                            <img class="about__logo-img" src="img/about__logo--aramark.png" alt="Aramark - logo">
                        </a>
                    </li>

                    <li class="about__logo">
                        <a class="about__logo-link" href="http://www.beeconsulting.cz/">
                            <img class="about__logo-img" src="img/about__logo--beeconsulting.png" alt="BeeConsulting - logo">
                        </a>
                    </li>

                </ul>
                <!-- /.about__logos -->

            </div>
            <!-- /.webwidth -->

        </article>
    </section>














    <section class="speakers" id="prednasejici">

        <div class="webwidth">

            <h1>Přenášející</h1>

            <ul class="speakers__list">

                <li class="speakers__speaker">

                    <div class="speakers__photo">
                        <div class="speakers__photo-img-wrapper">
                            <img class="speakers__photo-img" src="img/speakers__ing-petr-slezak.jpg" alt="Ing. Petr Slezák">
                        </div>
                    </div>
                    <!-- /.__photo -->

                    <div class="speakers__content">

                        <h2 class="speakers__name">Ing. Petr Slezák</h2>

                        <p class="speakers__job">personální ředitel VEOLIA</p>

                        <p>Petr Slezák, ve vrcholovém řízení lidských zdrojů působí více než 20 let. V současné době působí od roku 2003 na pozici HR Director for Continental Europe ve společnosti VEOLIA.</p>

                        <p>Před tím působil v roli HR Director ve společnostech TESCO, HILTON či SODEXHO. Vystudoval Vysokou školu ekonomickou v Praze.</p>

                    </div>
                    <!-- /.__content -->

                </li>
                <!-- /.speakers__speaker -->

                <li class="speakers__speaker">

                    <div class="speakers__photo">
                        <div class="speakers__photo-img-wrapper">
                            <img class="speakers__photo-img" src="img/speakers__ing-vladimira-carroll.jpg" alt="Ing. Vladimíra Carroll">
                        </div>
                    </div>
                    <!-- /.__photo -->

                    <div class="speakers__content">

                        <h2 class="speakers__name">Ing. Vladimíra Carroll</h2>

                        <p class="speakers__job">Head of HR at Central European Media Enterprises</p>

                        <p>Vladimíra Carroll vystudovala obchod a mezinárodní politiku na Vysoké škole ekonomické v Praze a program Central European Management Studies na Norwegian School of Economics & Business Administration. V oblasti lidských zdrojů působí již více než 14 let. Před nástupem do top managementu skupiny CME zastávala pozici HR Director v mezinarodnim holdingu Hame a v Pivovarech Staropramen pro český a slovenský trh. Dříve pracovala na manažerských pozicích v oddělení poradenství v oblasti řízení lidských zdrojů globální společnosti Deloitte, kde získala mnohaleté zkušenosti z množství především nadnárodních projektů napříč všemi sektory v USA a Evropě.</p>

                    </div>
                    <!-- /.__content -->

                </li>
                <!-- /.speakers__speaker -->

            </ul>
            <!-- /.speakers__list -->

        </div>

    </section>

    <section class="ticket" id="vstupenka">

        <div class="webwidth">

            <div class="ticket__ticket" id="buy-ticket">

                <h1>Koupit<br> vstupenku</h1>

                <p>Cena obsahuje:</p>

                <ul>
                    <li>vstup na odborné přednášky</li>
                    <li>prohlídku Muzea pražského vodárenství</li>
                    <li>občerstvení po celou dobu trvání akce</li>
                    <li>20 % slevu na vstupenku na 3.ročník HR Summitu, který se bude konat dne 15.5.2019</li>
                </ul>

                <div class="ticket__price">
                    <strong>2 950 Kč</strong>
                    <span>bez DPH</span>
                </div>				

                <a href="#" class="ticket__buy" id="buy-ticket-button">Koupit</a>

            </div>

        </div>

    </section>

    <section id="buy-ticket-form">
        <a href="#" id="buy-ticket-form-close">×</a>
        <h1>Objednat vstupenku</h1>
        <form>
            <div class="row row-2">
                <label for="buy-name">Jméno</label>
                <label for="buy-surname">Příjmení</label>
                <input type="text" name="name" id="buy-name">
                <input type="text" name="surname" id="buy-surname">
            </div>
            <div class="row row-2">
                <label for="buy-phone">Telefon</label>
                <label for="buy-email">Email</label>
                <input type="text" name="phone" id="buy-phone">
                <input type="text" name="email" id="buy-email">
            </div>
            <div class="row row-3">
                <label for="buy-company">Název firmy</label>
                <label for="buy-ic">IČ</label>
                <label for="buy-dic">DIČ</label>
                <input type="text" name="company" id="buy-company">
                <input type="text" name="ic" id="buy-ic">
                <input type="text" name="dic" id="buy-dic">
            </div>
            <div class="row row-3">
                <label for="buy-street">Ulice a č. p.</label>
                <label for="buy-city">Město</label>
                <label for="buy-zip">PSČ</label>
                <input type="text" name="street" id="buy-street">
                <input type="text" name="city" id="buy-city">
                <input type="text" name="zip" id="buy-zip">
            </div>

            <button type="submit" class="ticket__buy">Koupit vstupenku</button>
        </form>
    </section>

    <section id="kontakt">

        <section id="place">

            <div class="webwidth">

                <div class="place__event-title" role="presentation">HR MeetUp</div>

                <h2>Místo konání</h2>

                <address>
                    <p>
                        Historické prostory muzea Pražského vodárenství
                        <br>Podolská vodárna, Praha 4 - Podolí
                    </p>
                </address>

            </div>

        </section>

        <section id="contact-form">
            <div class="webwidth">
                <h1>Napište nám</h1>
                <form method="post">
                    <div class="contact-form">
                        <div class="odd">
                            <label for="contact-name">Jméno</label>
                            <input id="contact-name" type="text" name="name">
                        </div>
                        <div class="even">
                            <label for="contact-email">Email</label>
                            <input id="contact-email" type="text" name="email">
                        </div>
                        <div class="full">
                            <label for="contact-message">Text zprávy</label>
                            <textarea id="contact-message" name="message"></textarea>
                        </div>
                    </div>

                    <button type="submit">Odeslat</button>
                </form>
            </div>
        </section>
    </section>

    <footer id="main-footer">
        <div class="webwidth">
            <div id="footer-nav">
                <ul class="main-nav">
                    <li><a href="#">Domů</a></li>
                    <li><a href="#o-hr-meetup">O HR MeetUp</a></li>
                    <li><a href="#prednasejici">Přednášející</a></li>
                    <li><a href="#kontakt">Kontakt</a></li>
                    <li><a href="#buy-ticket" class="green">Koupit vstupenku</a></li>
                </ul>
                <img src="img/logo-yellow.svg" class="logo">			
            </div>

            <div id="copyright">
                <a href="http:beeonline.cz" target="_blank" title="Vytvořilo webové studio BeeOnline"><img src="img/beeonline.svg" class="developer"></a>
                <p>BeeOnline &copy; 2018 Všechna práva vyhrazena</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="image-map-resizer/js/imageMapResizer.min.js"></script>
    <script src="main.js?v=3"></script>


</body>
</html>