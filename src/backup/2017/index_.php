<?
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

	$text = 'Dobrý den,<br><br>děkujeme za nákup vstupenky na Czech HR Summit.<br><br>Uhraďte prosím částku <b>'.($_POST['qty'] * 5999).' Kč</b> na účet číslo <b>6657891001/5500</b>. Jako variabilní symbol zadejte <b>'.date("Y").$vs.'</b>.<br><br>
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
				<b>Datum splatnosti:</b> 12. 5. 2017
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
						<td>Vstupenka - Czech HR Summit</td>
						<td align="right">1 ks</td>
						<td align="right">'.round( ($_POST['qty'] * 5999) / 1.21, 2 ).' Kč</td>
						<td align="right">21 %</td>
						<td align="right">'.($_POST['qty'] * 5999).' Kč</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right" style="border: 1px solid #000; padding:5px; font-size:18px;">
				Celková cena s DPH: <b>'.($_POST['qty'] * 5999).' Kč</b>
			</td>
		</tr>
	</table>
	<i>Toto není daňový doklad</i>

	<br><br>S přáním hezkého dne,<br>
	Tým Czech HR Summit<br>
	www.hrsummit.cz';
	email($_POST['email'], "Vstupenka - Czech HR Summit", 'Czech HR Summit', 'info@hrsummit.cz',$text);
	
	if($_POST['job']) $_POST['job'] = ' - ' . $_POST['job'];

	email('info@hrsummit.cz', "Vstupenka - Czech HR Summit", $_POST['name'], $_POST['email'],'<b>Právě byl proveden nový nákup vstupenky na webu hrsummit.cz od '.$_POST['name'] . $_POST['job'] .'.<br><br>KOPIE EMAILU ZASLANÉHO ZÁKAZNÍKOVI:<br><br></b>'.$text);

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
	<title>Czech HR Summit</title>
	<link rel="shortcut icon" type="image/png" href="img/fav.png"/>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700" rel="stylesheet">
	<link href="main.css?3" rel="stylesheet">

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
		<p>Uhraďte prosím částku <b><?= $_POST['qty'] * 5999 ?> Kč</b> na účet číslo <b>6657891001/5500</b>. Jako variabilní symbol zadejte <b><?= date("Y") . $vs ?></b></p>
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
				<li><a href="#o-summitu">O summitu</a></li>
				<li><a href="#prednasejici">Mluvčí</a></li>
				<li><a href="#program">Program</a></li>
				<li><a href="#partnerstvi">Partnerství</a></li>
				<li><a href="#kontakt">Kontakt</a></li>
				<li><a href="#vstupenka" class="green">Vstupenka</a></li>
			</ul>
		</nav>
		<div id="mobile-links">
			<a href="#vstupenka" id="mobile-ticket-link" class="scroll-to-href">Vstupenka</a>
			<a href="#" id="mobile-menu-link">Menu</a>
		</div>
		<a href="#" class="scroll-to-href"><img src="img/logo.svg" class="logo"></a>
	</div>
</header>

<section id="home">
	<div class="webwidth">
		
		<div id="intro">
			<img src="img/logo-vertical.svg" id="vertical-logo">
			<img src="img/slogan.svg?2" id="slogan">
		</div>

		<article id="o-summitu">
			<h1>O summitu</h1>
			<p>Zaměstnanost, nebo nezaměstnanost</p>
	 		<p>Rok 2017 bude vskutku zajímavý, a to jak pro majitele firem, tak i pro samotný HR, který prochází fundamentální změnou. Ti, kteří tápou, „ty“ skutečné informace se mohou dozvědět na zásadní HR události tohoto roku. Tou je Czech HR summit, na kterém se od renomovaných osobností HR a byznysu dozvíte relevantní informace a jejich know-how, jak nakopnout svou firmu nahoru a jak se netřást před personalistickými výzvami. Buďme konkrétní, aby vás neděsila otázka: Kde hledat kvalifikované zaměstnance?</p>
	 		<p>Odpovědi na tuto a jiné otázky se dozvíte v několika blocích. Tedy, jak najít své zaměstnance, jak je motivovat a hlavně, jak si je udržet.</p>
	 		<p>Tedy, pro koho je vlastně summit určen? Pro generální, personální nebo obchodní ředitele, majitele a jednatele firem, začínající start-upy, HR specialisty a konzultanty.</p>
	 		<p>Pokud se chcete dozvědět, jak být o krok napřed, tak byste neměli chybět.</p>
		</article>
	</div>
</section>

<div class="bg-pattern-1"></div>


<article id="profil-1" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-1-color.jpg">
	</div>

	<div class="profil-description">
		<h1>Jiří Fabián<span>PURPOSEFLY – zakladatel a majitel</span></h1>
		<p>Po dlouhé štaci práce pro korporace se Jiří Fabián vrhnul do nejistých vod startupů (JetMinds, TopMonks, PurposeFly). Jejich společným jmenovatelem je uvolněné prostředí, podporující inovaci a dávající smysl. Životním posláním Jiřího je výchova dalších svobodně myslících lidí a tvorba masivně škálujících společností.</p>
		<p class="profil-program"><span>Téma</span> | Jak nastavit spravedlivé podmínky v týmu</p>
	</div>
</article>

<article id="profil-2" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-2-color.jpg">
	</div>

	<div class="profil-description">
		<h1>Lenka Benetková<span>Obchodní ředitelka – divize Euro</span></h1>
		<p>Lenka Benetková začala svou kariéru v DEN a.s., jako regionální vedoucí prodeje inzerce. Poté zastávala stejnou pozici i ve vydavatelství Vltava-Labe-Press. V současnosti je obchodní ředitelkou Divize Euro, kde má na starosti tisk, on-line a eventy.</p>

		<p class="profil-program"><span>Téma</span> | Vedení a motivace týmu</p>
	</div>
</article>

<article id="profil-3" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-3-color.jpg">
	</div>

	<div class="profil-description">
		<h1>Pavel Barták<span>HRtech Senior Sales Executive</span></h1>
		<p>Pavel Barták na své současné pozici využívá manažerské zkušenosti získané dlouholetým působením ve společnostech jako Microsoft, Oracle a DellEMC. Po čtvrtstoletí v IT se rozhodl změnit svou profesi a zařídil se do komunity dodávající platformu pro HR digitální transformaci.</p>

		<p class="profil-program"><span>Téma</span> | Recruiting Reality show</p>
	</div>
</article>

<article id="profil-4" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-4-color.jpg">
	</div>

	<div class="profil-description">
		<h1>PETR LUCKÝ<span>Výkonný ředitel DATACENTRUN Systems &amp; consulting, a.s.</span></h1>
		<p>V současnosti působí jako výkonný ředitel DATACENTRUM systems & consulting a.s. V předchozích letech působil ve společnosti rovněž v pozicích manažera oddělení informačních systémů či oddělení outsourcingu mezd a HR procesů. Předtím pracoval ve společnostech ArcelorMittal Ostrava, Vítkovice Steel, Národní bance Slovenska, TP Vision nebo Unicreditbank v ČR a SR.</p>

		<p class="profil-program"><span>Téma</span> | Zvyšování výkonnosti a produktivity v HR týmech</p>
	</div>
</article>

<article id="profil-5" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-5-color.jpg">
	</div>

	<div class="profil-description">
		<h1>MARTIN SKALICKÝ<span>Ředitel nemovitostního fondu EDULIOS</span></h1>
		<p>Martin Skalický se přes 20 let aktivně angažuje v oblasti trhu s nemovitostmi. V roce 1993 nastoupil do společnosti Healey&Baker (později Cushman&Wakefield) a zahájil tak profesní kariéru v segmentu komerčních nemovitostí. Poté se stal členem představenstva společnosti REICO, investiční společnost České spořitelny, kde se poté stal i generálním ředitelem. Od září 2015 působí v CIMEX Group na pozici ředitele nemovitostního fondu Edulios</p>

		<p class="profil-program"><span>Téma</span> | Úspěšní manažeři vydělávají peníze, my jim nabízíme možnost bezpečného investování</p>
	</div>
</article>

<article id="profil-6" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-6-color.jpg">
	</div>

	<div class="profil-description">
		<h1>ZUZANA DVOŘÁKOVÁ<span>Výkonná ředitelka Institutu personalistiky, Fakulta podnikohospodářská, VŠE</span></h1>
		<p>Výkonná ředitelka Institutu personalistiky Fakulty podnikohospodářské, VŠE Praha. Absolventka Fakulty výrobně-ekonomické VŠE v Praze – obor ekonomika průmyslu. V roce 1990 se stala kandidátkou ekonomických věd v oboru ekonomika průmyslu. V roce 2000 pak docentkou v oboru podniková ekonomika a management VŠE v Praze, kde se v roce 2006 se stala profesorkou. Řešitelka a spoluřešitelka projektů pro GA ČR, NVF – Phare, MŠMT, VÚBP, MPSV a TA ČR v oblasti vzdělávání a rozvoje lidských zdrojů.</p>

		<p class="profil-program"><span>Téma</span> | Příprava budoucích lídrů</p>
	</div>
</article>

<article id="profil-7" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-7-color.jpg">
	</div>

	<div class="profil-description">
		<h1>IVETA VOLFOVÁ<span>Personální ředitelka společnosti ALZA</span></h1>
		<p>Absolventka VŠE Praha. Poté pracovala jako konzultantka společnosti Deloitte & Touche. Účastnila se projektu USAID pro Ministerstvo privatizace. Od roku 1996 stála u založení T-Mobile Czech Republic (původně RadioMobil), kde pracovala na pozici manažerky a následně ředitelky lidských zdrojů. Od roku 2006 pracovala pro finanční skupinu PPF, kde řídila projekt HR Re-engineeringu v Home Credit and Finance Bank v Moskvě a následně nastoupila jako Ředitelka lidských zdrojů společnosti PPF a.s. Aktuálně je HR ředitelka Alza.cz se zodpovědností za Evropu.</p>

		<p class="profil-program"><span>Téma</span> | Jak to dělat jinak?
	</div>
</article>

<article id="profil-8" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-8-color.jpg">
	</div>

	<div class="profil-description">
		<h1>GABRIEL BERDÁR<span>Majitel společnosti BVI, </br>ex GM Český Telecom</span></h1>
		<p>Gabriel Berdár začínal svou kariéru v roce 1990 na pozici obchodního manažera ve Strojimprotu a.s., poté pracoval například pro IBM s.r.o, IDOM s.r.o., XEROX ČR s.r.o., DELL COMPUTERS s.r.o. a také v Českém Telecomu a.s., na pozici generálního ředitele. </p>

		<p class="profil-program"><span>Téma</span> | Řízení a motivace lidí v různých stupních vývoje</p>
	</div>
</article>

<article id="profil-9" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-9-color.jpg">
	</div>

	<div class="profil-description">
		<h1>Kateřina Sadílková<span>Generální ředitelka úřadu práce ČR</span></h1>
		<p>V roce 2005 absolvovala UK FF Praha v oboru Andragogika a řízení lidských zdrojů. Na téže univerzitě získala v roce 2008 titul PhDr. V letech 2006 – 2011 řídila Úřad práce v Liberci. V roce 2011 byla jmenována první generální ředitelkou Úřadu práce ČR. Následně působila v řídících funkcích ve státní správě. Od roku 2013 byla ředitelkou Krajské pobočky ÚP ČR v Liberci. Od října 2014 působila rok jako zastupující generální ředitelka Úřadu práce ČR. Dne 2. 11. 2015 byla na základě výsledků řádného výběrového řízení jmenována státním tajemníkem MPSV do funkce generální ředitelky. Má zkušenosti z oblasti trhu práce, řízení lidských zdrojů a vzdělávání dospělých. Je předsedkyní Rady pro rozvoj lidských zdrojů v Libereckém kraji, předsedkyní Výkonné rady Paktu zaměstnanosti Libereckého kraje a členkou řady dalších komunikačních platforem v oblasti problematiky trhu práce.</p>

		<p class="profil-program"><span>Téma</span> | Situace na pracovním trhu</p>
	</div>
</article>

<article id="profil-moderator" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/moderator-color.jpg">
	</div>

	<div class="profil-description">
		<h1>Jan Smetana<span>Moderátor</span></h1>
		<p>Jan Smetana je od dubna roku 2001 externím spolupracovníkem Redakce sportu ČT. Mezi jeho aktivity v ČT patří komentování basketbalové Euroligy mužů i žen, zápasy Mattoni NBL, ŽBL i národních týmů. V letech 2005-2011 natáčel a moderoval pořad Time out. Od roku 2006 moderuje sportovní zprávy Studia 6 a na ČT 24. Od roku 2012 moderuje hlavní sportovní zpravodajství ČT - Branky Body Vteřiny. V roce 2014 vyhrál konkurz na moderátora zábavné vědomostní soutěže Míň je víc! Na obrazovky byla soutěž uvedena v lednu 2015.</p>

	</div>
</article>

<section id="prednasejici">
	<div class="webwidth">
		<h1>Mluvčí</h1>
		<ul id="speakers">
			<li class="speaker">
				<div class="photo-wrapper bg-3">
					<div class="photo" style="background-image: url('img/speaker-3.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Pavel <span>Barták</span></h2>
					<p>HRtech Senior Sales Executive</p>
					<a href="#" data-id="profil-3">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-2">
					<div class="photo" style="background-image: url('img/speaker-2.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Lenka <span>Benetková</span></h2>
					<p>Obchodní ředitelka – divize Euro</p>
					<a href="#" data-id="profil-2">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-1">
					<div class="photo" style="background-image: url('img/speaker-8.jpg');;"></div>	
				</div>
				<div class="speaker-info">
					<h2>Gabriel <span>Berdár</span></h2>
					<p>Majitel společnosti BVI, ex GM Český Telecom</p>
					<a href="#" data-id="profil-8">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-2">
					<div class="photo" style="background-image: url('img/speaker-6.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Zuzana <span>Dvořáková</span></h2>
					<p style="letter-spacing: -0.1px;">Výkonná ředitelka Institutu personalistiky, Fakulta podnikohospodářská, VŠE</p>
					<a href="#" data-id="profil-6">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-1">
					<div class="photo" style="background-image: url('img/speaker-1.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Jiří <span>Fabián</span></h2>
					<p>PURPOSEFLY – zakladatel  a majitel</p>
					<a href="#" data-id="profil-1">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-3">
					<div class="photo" style="background-image: url('img/speaker-4.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Petr <span>Lucký</span></h2>
					<p>Výkonný ředitel DATACENTRUN Systems &amp; consulting, a.s.</p>
					<a href="#" data-id="profil-4">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-3">
					<div class="photo" style="background-image: url('img/speaker-9.jpg');;"></div>	
				</div>
				<div class="speaker-info">
					<h2>Kateřina <span>Sadílková</span></h2>
					<p>Generální ředitelka úřadu práce ČR</p>
					<a href="#" data-id="profil-9">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-2">
					<div class="photo" style="background-image: url('img/speaker-5.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Martin <span>Skalický</span></h2>
					<p>Ředitel nemovitostního fondu EDULIOS</p>
					<a href="#" data-id="profil-5">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-2">
					<div class="photo" style="background-image: url('img/speaker-7.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Iveta <span>Volfová</span></h2>
					<p>Personální ředitelka společnosti ALZA</p>
					<a href="#" data-id="profil-7">Zobrazit profil</a>
				</div>
			</li>
		</ul>
	</div>
</section>

<section id="program">
	<div class="webwidth">
		<h1>Program</h1>
		<div id="moderator" data-id="profil-moderator" class="program-tabs" style="border: 2px solid #FFB900; cursor: pointer; margin: 60px auto; width: 50%;">
			<div class="program-time">
				<img src="img/moderator.jpg" width="140" style="display: block;">
			</div>
			
			<div class="program-description">
				<h2><strong>Jan Smetana</strong><br><span style="font-size: 20px;">Moderátor akce</span></h2>
				<p><a href="#" style="text-transform: uppercase; color: #000;">Zobrazit profil</a></p>
			</div>
		</div>

		<ul class="program-nav">
			<li><a href="#" data-tab="1" class="active">Dopolední blok</a></li><!--
		 --><li><a href="#" data-tab="2">Odpolední blok</a></li>
		</ul>

		<div class="program-tabs">
		
			<div class="program-tab" data-tab="1">
				<ul>
					<li>
						
						<div class="program-time">
							8:30<br>-<br>9:30
						</div>
						
						<div class="program-description">
							<h2><strong>Registrace, networking</strong></h2>
						</div>
					</li>
					<li>
						<!--
						<div class="program-time">
							8:30<br>-<br>9:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Zahájení konference, úvodní slovo</strong></h2>
							<p>Šárka Fričová – jednatelka společnosti a zakladatelka divize BeeConsulting</p>
						</div>
					</li>
					<li>
						<!--
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Pavel Barták:</strong> „Recruiting Reality show“</h2>
							<p>Jak najít, motivovat a jak si je udržet nové zaměstnance? Jak to dělá SAP SuccessFactors a jaké má vlastní zkušenosti s náborem na těžko obsaditelné pozice?</p>
						</div>
					</li>
					<li>
						<!--
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Lenka Benetková:</strong> „Vedení a motivace týmu“</h2>
						</div>
					</li>
					<li>
						<!--
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Gabriel Berdár:</strong> „Řízení a motivace lidí v různých stupních vývoje“</h2>
						</div>
					</li>
					<li>
						<!--
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Zuzana Dvořáková:</strong> „Příprava budoucích lídrů“</h2>
						</div>
					</li>
					<li>
						<!--
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Společný oběd</strong></h2>
						</div>
					</li>
				</ul>
			</div>

			<div class="program-tab" data-tab="2">
				<ul>
					<li>
						<!--
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Jiří Fabián:</strong> „Jak nastavit spravedlivé podmínky v týmu“</h2>
							<p>Kterak zatraktivnit Váš tým pro nábor? Probuďte Váš tým – tipy pro zvyšování angažovanosti.</p>
						</div>
					</li>
					<li>
						<!--
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Petr Lucký:</strong> „Zvyšování výkonnosti a produktivity v HR týmech“</h2>
							<p>Jaké existují možnosti zvyšování výkonnosti a produktivity ve společnostech v oblasti HR procesů? Definice personálních procesů, jejich elektronizace, zlepšení podpory zaměstnanců v oblasti personálních procesů či outsourcing mezd a personální agendy pomohly výrazně zvýšit produktivitu práce již mnoha velkým firmám.</p>
						</div>
					</li>
					<li>
						<!--
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Kateřina Sadílková:</strong> „Situace na pracovním trhu“</h2>
						</div>
					</li>
					<li>
						<!--
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Martin Skalický:</strong> „Úspěšní manažeři vydělávají peníze, my jim nabízíme možnost bezpečného investování“</h2>
							<p>Potřeby současného HR mohou narážet na potřebu flexibilního využití kanceláří a zasedacích místností. Jak funguje řešení Right Now Offices? </p>
						</div>
					</li>
					<li>
						<!--
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						-->
						<div class="program-description">
							<h2><strong>Iveta Volfová:</strong> „Jak to dělat jinak?“</h2>
							<p>Můžeme hledat zaměstnance ještě jinde? Nebo máme slevit ze svých požadavků na kandidáty, když jich je nedostatek?</p>
						</div>
					</li>
					<li>
						
						<div class="program-time">
							16:30
						</div>
						
						<div class="program-description">
							<h2><strong>Závěrečná diskuse a ukončení akce</strong></h2>
						</div>
					</li>
				</ul>
			</div>

		</div>
	</div>
</section>

<section id="partnerstvi">
	<div class="webwidth">
		<h1>Partnerství</h1>
		<p>Czech HR Summit by nevznikl bez strategických partnerů, kteří ví, kudy se bude ubírat další vývoj HR.<br>Děkujeme tedy všem, kdo se na zásadní události české personalistiky podíli. Děkujeme!</p>
		<img src="img/partneri-desktop-new.png" class="desktop" usemap="#partneri-desktop-map">
		<map name="partneri-desktop-map" id="partneri-desktop-map" style="width: 100%">
			<area  alt="" title="" href="http://www.ceskaposta.cz/" shape="rect" coords="265,14,723,186" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.successfactors.com/" shape="rect" coords="728,14,1186,186" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.veolia.cz/cs" shape="rect" coords="1199,13,1657,185" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://edulios.cz/" shape="rect" coords="0,348,253,451" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.hotel-cosmopolitan.cz/cz/" shape="rect" coords="251,325,477,474" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.datacentrum.cz/" shape="rect" coords="473,329,763,467" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.edumenu.cz/" shape="rect" coords="767,322,1058,471" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.profihr.cz/" shape="rect" coords="1078,320,1266,458" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.cupradlo.cz/" shape="rect" coords="1294,325,1482,463" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.rightnowoffices.cz/" shape="rect" coords="0,471,273,620" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.businessinstitut.cz/" shape="rect" coords="275,493,559,607" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.grada.cz/" shape="rect" coords="567,487,916,601" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.klubpersonalistu.cz/cz/" shape="rect" coords="911,489,1318,605" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.multisport.cz/cs/" shape="rect" coords="1333,495,1626,607" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.pibs.cz/cz/" shape="rect" coords="1511,306,1687,472" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.euro.cz/" shape="rect" coords="1704,307,1880,473" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.reproteam.cz/" shape="rect" coords="1632,478,1880,593" style="outline:none;" target="_blank"/>
		</map>

		<img src="img/partneri-tablet-new.png" class="tablet" usemap="#partneri-tablet-map">
		<map name="partneri-tablet-map" id="partneri-tablet-map">
			<area  alt="" title="" href="http://www.ceskaposta.cz/" shape="rect" coords="0,0,626,220" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.successfactors.com/" shape="rect" coords="642,2,1364,222" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.veolia.cz/cs" shape="rect" coords="1393,13,1880,218" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://edulios.cz/" shape="rect" coords="0,291,444,480" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.hotel-cosmopolitan.cz/cz/" shape="rect" coords="494,262,834,508" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.datacentrum.cz/" shape="rect" coords="902,279,1361,504" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.edumenu.cz/" shape="rect" coords="1442,293,1880,478" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.profihr.cz/" shape="rect" coords="0,556,344,741" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.cupradlo.cz/" shape="rect" coords="453,530,782,765" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.rightnowoffices.cz/" shape="rect" coords="845,517,1314,752" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.businessinstitut.cz/" shape="rect" coords="1389,523,1858,758" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.grada.cz/" shape="rect" coords="0,796,589,956" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.klubpersonalistu.cz/cz/" shape="rect" coords="615,782,1297,942" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.multisport.cz/cs/" shape="rect" coords="1347,796,1807,944" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.reproteam.cz/" shape="rect" coords="204,993,737,1166" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.pibs.cz/cz/" shape="rect" coords="847,948,1167,1197" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.euro.cz/" shape="rect" coords="1348,989,1664,1164" style="outline:none;" target="_blank"/>
		</map>
	</div>
</section>

<div class="bg-pattern-2"></div>

<section id="vstupenka">
	<div class="webwidth">
		<div class="ticket" id="buy-ticket">
			<h1>Koupit<br>vstupenku</h1>
			<br><div class="ticket-price">
				<strong>4.956 Kč</strong>
				<span>bez DPH</span>
			</div>				
			<a href="#" class="buy" id="buy-ticket-button">Koupit</a>
		</div>
	</div>
</section>

<section id="buy-ticket-form">
	<a href="#" id="buy-ticket-form-close">×</a>
	<form method="post" action="#vstupenka">
		<input type="number" name="qty" id="qty" value="1" required style="float: right; width: 100px;">
		<label for="qty" style="float: right; padding: 0; line-height: 43px;">Počet&nbsp;&nbsp;&nbsp;</label>
		<h1 style="text-align: left;">Objednat vstupenku</h1>
		<div class="row row-2">
			<label for="buy-name">Jméno</label>
			<label for="buy-surname">Příjmení</label>
			<input type="text" name="name" id="buy-name">
			<input type="text" name="surname" id="buy-surname">
		</div>
		<div class="row row-2">
			<label for="buy-phone">Telefon</label>
			<label for="buy-email">Email</label>
			<input type="text" required name="phone" id="buy-phone">
			<input type="text" required name="email" id="buy-email">
		</div>
		<div class="row row-3">
			<label for="buy-street">Ulice a č.p.</label>
			<label for="buy-city">Město</label>
			<label for="buy-zip">PSČ</label>
			<input type="text" required name="street" id="buy-street">
			<input type="text" required name="city" id="buy-city">
			<input type="text" required name="zip" id="buy-zip">
		</div>
		<div id="company-data">
			<a href="#" id="company-data-show">Chci zadat firemní údaje</a>
			<div class="row row-2">
				<label for="buy-company">Firma</label>
				<label for="buy-job">Pozice</label>
				<input type="text" placeholder="nepovinné" name="company" id="buy-company">
				<input type="text" placeholder="nepovinné" name="job" id="buy-job">
			</div>
			<div class="row row-2">
				<label for="buy-ic">IČ</label>
				<label for="buy-dic">DIČ</label>
				<input type="text" placeholder="nepovinné" name="ic" id="buy-ic">
				<input type="text" placeholder="nepovinné" name="dic" id="buy-dic">
			</div>
		</div>
		<div>
			<label for="vop"><input type="checkbox" required name="vop" id="vop"> Souhlasím s <a href="vop.pdf" target="_blank" style="color: #06AD49;">obchodními podmínkami</a></label>
		</div>

		<button type="submit" class="buy" name="buy">Koupit vstupenku</button>
	</form>
</section>

<section id="kontakt">
	<section id="place">
		<div class="webwidth">
			<strong>HOTEL COSMOPOLITAN</strong>
			<span>Zlatnická 3<br>Praha 1, 110 00</span>
		</div>
	</section>
	<section id="contact-form">
		<div class="webwidth">
			<h1>Napište nám</h1>
			<form method="post">
				<div class="contact-form">
					<div class="odd">
						<label for="contact-name">Jméno</label>
						<input id="contact-name" type="text" name="name" required>
					</div>
					<div class="even">
						<label for="contact-email">Email</label>
						<input id="contact-email" type="text" name="email" required>
					</div>
					<div class="full">
						<label for="contact-message">Text zprávy</label>
						<textarea id="contact-message" name="message" required></textarea>
					</div>
				</div>

				<button type="submit" name="send">Odeslat</button>
			</form>
		</div>
	</section>
</section>

<footer id="main-footer">
	<div class="webwidth">
		<div id="footer-nav">
			<ul class="main-nav">
				<li><a href="#">Domů</a></li>
				<li><a href="#o-summitu">O summitu</a></li>
				<li><a href="#prednasejici">Mluvčí</a></li>
				<li><a href="#program">Program</a></li>
				<li><a href="#partnerstvi">Partnerství</a></li>
				<li><a href="#kontakt">Kontakt</a></li>
				<li><a href="#vstupenka">Vstupenka</a></li>
			</ul>
			<img src="img/logo-yellow.svg" class="logo">			
		</div>
		
		<div id="copyright">
			<a href="http://www.beeonline.cz" target="_blank" title="Vytvořilo webové studio BeeOnline"><img src="img/beeonline.svg" class="developer"></a>
			<p>BeeMedia, s.r.o. &copy; <?= date("Y") ?> Všechna práva vyhrazena</p>
		</div>
	</div>
</footer>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="image-map-resizer/js/imageMapResizer.min.js"></script>
<script src="main.js?2"></script>


</body>
</html>