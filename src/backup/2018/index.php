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

	$text = 'Dobrý den,<br><br>děkujeme za nákup vstupenky na Czech HR Summit.<br><br>Uhraďte prosím částku <b>'.($_POST['qty'] * 6655).' Kč</b> na účet číslo <b>6657891001/5500</b>. Jako variabilní symbol zadejte <b>'.date("Y").$vs.'</b>.<br><br>
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
				<li><a href="#o-summitu">O summitu</a></li>
				<li><a href="#prednasejici">Mluvčí</a></li>
				<!---<li><a href="#program">Program</a></li>--->
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
			<img src="img/logo_odesilani.png" id="vertical-logo">
			<img src="img/slogan_odesilani.png" id="slogan">
		</div>

		<article id="o-summitu">
			<h1>O summitu</h1>  
<p>HR Summit 2018 bude opět zásadní, provede robotikou.</p>
<p>I tentokrát bude mít summit spektakulární téma, které přitahuje pozornost všech profesionálů, kteří ví, že se bude dít něco velkého.
Tato problematika je strašákem mnoha personalistů a HR manažerů a v blízké budoucnosti bude měnit osudy mnoha lidí.
Nástupem nové průmyslové revoluce jsou ohroženy různé skupiny zaměstnanců a na druhou stranu je adekvátní vývoj nových pozic, o kterých jsme před pěti lety neměli ani představu, že budou existovat.</p>
<p>Tato tématika zasahuje mnoho oborů a je třeba se začít přizpůsobovat zrychlujícímu se vývoji techniky. To samozřejmě ovlivňuje trend starých pracovních pozic a naopak pomáhá k obrovskému rozmachu nových pracovních míst. Stejně jako se změnila situace v oblasti HR v době nízké nezaměstnanosti, je třeba efektivně reagovat i na tuto výzvu.  </p>
<p>Průmysl 4.0 se obrovsky promítne do HR a příprava na velké změny jsou zapotřebí již teď. S příchodem čtvrté průmyslové revoluce vidí společnosti zpravidla příležitost pro zvýšení produktivity práce, to ale není vše. Robotizace přináší mnoho dalších aspektů v dalších segmentech HR od hloubkové analytiky big dat přes sociální mapy až po pokročilé docházkové systémy, založené na kamerový snímcích zaměstnance hodnotící mikro emoce v jeho obličeji. Složité algoritmy se pomalu začnou integrovat do dílčích procesů a samotný systém zaměstnávání bude lehčeji vyhodnotitelný.  </p>
<p>Tyto procesní změny se promítnou nejen do strategií společnosti od velikostí korporátních společností až po menší podniky, ale zasáhne i politiku zaměstnanosti. </p>
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
		<h1>VERONIKA IVANOVIČ<span>Viceprezidentka People & Property, Vodafone</span></h1>
		<p>Na různých HR postech v mezinárodních společnostech z finančního či FMCG sektoru působí Veronika přes 20 let a své zkušenosti nyní úročí v roli viceprezidentky pro lidské zdroje ve Vodafonu, kde má kromě klasického HR na starosti také oblasti správy majetku a komunikace, Vodafone Nadaci a agendu Udržitelného podnikání.</p>
		<p class="profil-program"><span>Téma</span> | #DigitalniCesko</p>
	</div>
</article>

<article id="profil-2" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-2-color.jpg">
	</div>

	<div class="profil-description">
		<h1>PETR BENEŠ<span>Technoptimista, kouč, spoluzakladatel 6D Academy</span></h1>
		<p>Petr Beneš působil přes 20 let v bankovnictví. Kromě České republiky pracoval v Polsku, USA a Thajsku, kde otevíral banku na zelené louce. Posledních 10 let pracoval jako CIO ve dvou českých bankách - v GE Money (Moneta) a v České spořitelně. Má osobní zkušenost s převodem velké firmy do prostředí veřejného cloudu.V roce 2016 spoluzaložil 6D Academy, která pomáhá firmám i jednotlivcům adaptovat se na technologické a společenské změny.</p>

		<p class="profil-program"><span>Téma</span> | Zbaví nás technologie lidskosti?</p>
	</div>
</article>

<article id="profil-3" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-3-color.jpg">
	</div>

	<div class="profil-description">
		<h1>ALAN BABICKÝ<span>Head of Consulting, Sprinx Systems</span></h1>
		<p>Alan Babický pracoval přes 10 let na obchodních pozicích několika mezinárodních firem. Poté se dal na golf. V roce 2005 založil organizaci věnující se jeho propagaci a výuce. Sbíral úspěchy jako hráč a dnes i jako trenér a hráčský agent. Vystudoval Vysokou školu ekonomickou v Praze a anglickou Institute of Technology.</p>

		<p class="profil-program"><span>Téma</span> | Masová individualizace vzhůru nohama</p>
	</div>
</article>

<article id="profil-4" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-4-color.jpg">
	</div>

	<div class="profil-description">
		<h1>PETR LUCKÝ<span>Výkonný ředitel DATACENTRUM Systems &amp; consulting, a.s.</span></h1>
		<p>V současnosti působí jako výkonný ředitel DATACENTRUM systems & consulting a.s. V předchozích letech působil ve společnosti rovněž v pozicích manažera oddělení informačních systémů či oddělení outsourcingu mezd a HR procesů. Předtím pracoval ve společnostech ArcelorMittal Ostrava, Vítkovice Steel, Národní bance Slovenska, TP Vision nebo Unicreditbank v ČR a SR.</p>

		<p class="profil-program"><span>Téma</span> | HR systém cesta k automatizaci procesů</p>
	</div>
</article>

<article id="profil-5" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-5-color.jpg">
	</div>

	<div class="profil-description">
		<h1>MARTINA SZTURC KÁŇOVÁ<span>OKIN BPS, HR ředitelka</span></h1>
		<p>Martina Szturc Káňová pracuje v oblasti řízení lidských zdrojů 17 let. Získala mnoho zkušenosti s budováním nově vznikajících zahraničních firem a nastavováním HR procesů „na zelené louce“. Třetím rokem pracuje ve společnosti OKIN BPS na pozici HR ředitelky v rámci skupiny OKIN. Dříve pracovala pro japonskou společnost Shimano v Karviné a společnost vyrábějící elektroniku Asus, nyní Pegatron. Působila také na pozici Manažerky lidských zdrojů ve společnosti GE Money Bank, a.s., kde měla komplexně na starosti oblast lidských zdrojů v Centru zákaznických služeb v Ostravě – Hrabové. Vystudovala Slezskou univerzitu, obor Marketing a management.</p>

		<p class="profil-program"><span>Téma</span> | HR orchestr </p>
	</div>
</article>

<article id="profil-6" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-6-color.jpg">
	</div>

	<div class="profil-description">
		<h1>LUKÁŠ FOGLAR<span>Business manager, Vodafone</span></h1>
		<p>Lukáš za své působení ve Vodafonu prošel několika pozicemi v procesní a projektové oblasti, aby zakotvil v čele týmu Zákaznické zkušenosti. Nyní na pozici Business manažer podporuje organizaci v definici a exekuci strategie – z velké části se pak podílí na realizaci programu Digitální transformace. Veškerý svůj volný čas věnuje cestování a objevování nových technologií.</p>

		<p class="profil-program"><span>Téma</span> | #DigitalniCesko</p>
	</div>
</article>

<article id="profil-7" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-7-color.jpg">
	</div>

	<div class="profil-description">
		<h1>RADEK PTÁČEK<span>Docent lékařské psychologie, klinický psycholog, soudní znalec</span></h1>
		<p>Docent lékařské psychologie, klinický psycholog, soudní znalec. Působí na Psychiatrické klinice 1. LF UK a University New York in Prague. Publikoval více než 150 původních odborných prací v domácích i zahraničních odborných časopisech, které dosáhly ve světě více než 400 citací. Je řešitelem nebo spoluřešitelem řady vědeckých grantů i projektů v sociální oblasti. Je členem redakčních rad několika významných zahraničních časopisů s impact factorem. </p>

		<p class="profil-program"><span>Téma</span> | Jak se nezbláznit z práce ani v době digitalizace?
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

		<p class="profil-program"><span>Téma</span> | Umělá inteligence a její možné dopady</p>
	</div>
</article>

<article id="profil-9" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-9-color.jpg">
	</div>

	<div class="profil-description">
		<h1>CTIRAD LOLEK<span>HR Director O2 pro Českou a Slovenskou republiku</span></h1>
		<p>Vystudoval obor vzdělávání dospělých se zaměřením na řízení lidských zdrojů na UP Olomouc. Od roku 1996 pracoval v HR odděleních společností ICEC Holding, Kappa Karton Moravia, EPCOS nebo Timken Česká republika. V posledně jmenované zastával postupně pozice územního manažera lidských zdrojů, manažera lidských zdrojů pro střední a jižní Evropu a statutárního zástupce firmy. V roce 2008 nastoupil do funkce personálního ředitele ArcelorMittal Ostrava. Aktuálně působí ve společnosti O2 Czech Republic na postu Human Resources Director pro Českou republiku a Slovensko.</p>

		<p class="profil-program"><span>Téma</span> | Automatizace, digitalizace a robotizace v rámci naší společnosti</p>
	</div>
</article>

<article id="profil-10" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-10-color.jpg">
	</div>

	<div class="profil-description">
		<h1>LUKÁŠ ŘEHA<span>Datlowe, Chief Operating Officer</span></h1>
		<p>Lukáš Řeha působil déle než 10 let v prostředí Business Process Services a Outsourcingu, kdy se podílel na řízení dodávky služeb a transformačních programů společnosti Verizon.
V posledních dvou letech se plně věnuje digitální transformaci a řídí technologickou společnost Datlowe/Techstra, která se specializuje na implementaci nových inovativních řešení do vnitropodnikových
procesů napříč různými segmenty podnikání a jejich následnou automatizací.
</p>

		<p class="profil-program"><span>Téma</span> | HR orchestr</p>
	</div>
</article>

<article id="profil-11" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-11-color.jpg">
	</div>

	<div class="profil-description">
		<h1>KAREL BÁREK<span>Hervis Sports ČR, Ředitel</span></h1>
		<p>Karel Bárek je absolventem fakulty mezinárodních vztahů na VŠE v Praze, obdržel titul M.A. „Economics of International Trade“ na Staffordshire Univerzitě ve Stoke-on-Trent a Univerzitě RUCA v Antverpách v roce 1997. 
Velkou část své pracovní kariéry prožil ve společnosti Tesco Stores ČR/SR, kde v roce 1997 začal jako „management trainee“. S postupem času zde zastával pozice obchodního ředitel Non-Food, ředitele pro komerční podporu a ředitele logistiky pro Česko a Slovensko.
V posledních čtyřech letech stále aktivně působí na poli maloobchodu v České republice a je ředitelem obchodního řetězce Hervis Sports ČR
</p>

		<p class="profil-program"><span>Téma</span> | Store Online Mobile</p>
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
					<h2>Alan <span>Babický</span></h2>
					<p>Head of Consulting, Sprinx Systems</p>
					<a href="#" data-id="profil-3">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-2">
					<div class="photo" style="background-image: url('img/speaker-2.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Petr <span>Beneš</span></h2>
					<p>Technoptimista, kouč, spoluzakladatel 6D Academy</p>
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
					<h2>Lukáš <span>Foglar<br /></span></h2>
					<p style="letter-spacing: -0.1px;">Business manager, Vodafone</p>
					<a href="#" data-id="profil-6">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-1">
					<div class="photo" style="background-image: url('img/speaker-1.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Veronika <span>Ivanovič<br></span></h2>
					<p>Viceprezidentka People & Property, Vodafone</p>
					<a href="#" data-id="profil-1">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-3">
					<div class="photo" style="background-image: url('img/speaker-5.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Martina <span>Szturc Káňová </span></h2>
					OKIN BPS, HR ředitelka
					<a href="#" data-id="profil-5">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-3">
					<div class="photo" style="background-image: url('img/speaker-9.jpg');;"></div>	
				</div>
				<div class="speaker-info">
					<h2>Ctirad <span>Lolek</span></h2>
					<p>HR Director O2 pro Českou a Slovenskou republiku</p>
					<a href="#" data-id="profil-9">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-2">
					<div class="photo" style="background-image: url('img/speaker-4.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Petr <span>Lucký</span></h2>
					<p>Výkonný ředitel DATACENTRUM Systems &amp; consulting, a.s.</p>
					<a href="#" data-id="profil-4">Zobrazit profil</a>
				</div>
			</li>
			<li class="speaker">
				<div class="photo-wrapper bg-2">
					<div class="photo" style="background-image: url('img/speaker-7.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Radek <span>Ptáček</span></h2>
					<p>Docent lékařské psychologie, klinický psycholog, soudní znalec</p>
					<a href="#" data-id="profil-7">Zobrazit profil</a>
				</div>
			</li>
      <li class="speaker">
				<div class="photo-wrapper bg-2">
					<div class="photo" style="background-image: url('img/speaker-10.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Lukáš <span>Řeha</span></h2>
					<p>Datlowe, Chief Operating Officer</p>
					<a href="#" data-id="profil-10">Zobrazit profil</a>
				</div>
			</li>
      <li class="speaker">
				<div class="photo-wrapper bg-3">
					<div class="photo" style="background-image: url('img/speaker-11.jpg');"></div>	
				</div>
				<div class="speaker-info">
					<h2>Karel <span>Bárek</span></h2>
					<p>Hervis Sports ČR, Ředitel</p>
					<a href="#" data-id="profil-11">Zobrazit profil</a>
				</div>
			</li>
		</ul>
	</div>
</section>
<!---------------
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
			<li><a href="javascript:void(0)" data-tab="1" id="tab1-top" onClick="$('#tab1-bottom').addClass('active');$('#tab2-bottom').removeClass('active');" class="active">Dopolední blok</a></li><!--
		 -->
     
     <!-----
     <li><a href="javascript:void(0)" id="tab2-top" onClick="$('#tab2-bottom').addClass('active');$('#tab1-bottom').removeClass('active');" data-tab="2">Odpolední blok</a></li>
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
						<div class="program-time">
							9:30
						</div>
						<div class="program-description">
							<h2><strong>Šárka Fričová:</strong> Zahájení conference</h2>
							<p>jednatelka a zakladatelka BeeConsulting</p>
						</div>
					</li>
					<li>
						<div class="program-time">
							9:30<br>-<br>10:00
						</div>
						<div class="program-description">
							<h2><strong>Zuzana Dvořáková:</strong> „Příprava budoucích lídrů“</h2>
							<p>výkonná ředitelka Institutu personalistiky, fakulta podnikohospodářská, VŠE</p>
						</div>
					</li>
					<li>
						<div class="program-time">
							10:00<br>-<br>10:30
						</div>
						<div class="program-description">
							<h2><strong>Gabriel Berdár:</strong> „Řízení a motivace lidí v různých stupních vývoje“</h2>
							<p>majitel společnosti BVI, ex GM Český Telecom </p>
						</div>
					</li>
					<li>
						<div class="program-time">
							10:30<br>-<br>10:50
						</div>
						<div class="program-description">
							<h2>přestávka na kávu</h2>
						</div>
					</li>
					<li>
						<div class="program-time">
							10:50<br>-<br>11:30
						</div>
						<div class="program-description">
							<h2><strong>Jiří Fabián:</strong> „Jak nastavit spravedlivé podmínky v týmu“ – 1. část</h2>
							<p>zakladatel a majitel PURPOSEFLY</p>
						</div>
					</li>
					<li>
						<div class="program-time">
							11:30<br>-<br>12:00
						</div>
						<div class="program-description">
							<h2><strong>Petr Lucký:</strong> “Zvyšování výkonnosti a produktivity v HR týmech“</h2>
							<p>výkonný ředitel DATACENTRUM Systems & consulting, a.s. </p>
						</div>
					</li>
					<li>
						<div class="program-time">
							<a name="tab2" id="tab2"></a>
							12:00<br>-<br>12:30
						</div>
						<div class="program-description">
							<h2><strong>Martin Skalický:</strong> „Úspěšní manažeři vydělávají peníze, my jim nabízíme možnost bezpečného investování“</h2>
							<p>ředitel nemovitostního fondu EDULIOS</p>
						</div>
					</li>
					<li>
						<div class="program-time">
							12:30<br>-<br>13:30
						</div>
						<div class="program-description">
							<h2>společný oběd</h2>
							<p>hotel Imperial, Café Imperial (salonek Topas)</p>
						</div>
					</li>					
				</ul>

			</div>
			<div class="program-tab" data-tab="2">

				<ul>
					<li>
						<div class="program-time">
							13:30<br>-<br>14:00
						</div>
						<div class="program-description">
							<h2><strong>Pavel Barták:</strong> „Recruiting Reality show“</h2>
							<p>SAP HRtech Senior Sales Executive</p>
						</div>
					</li>
					<li>
						<div class="program-time">
							14:00<br>-<br>14:15
						</div>
						<div class="program-description">
							<h2><strong>Kateřina Sadílková:</strong> „Situace na pracovním trhu“</h2>
							<p>generální ředitelka úřadu práce ČR</p>
						</div>
					</li>
					<li>
						<div class="program-time">
							14:15<br>-<br>14:45
						</div>
						<div class="program-description">
							<h2><strong>Iveta Volfová:</strong> „Jak to dělat jinak?“</h2>
							<p>Alza, personální ředitelka</p>
						</div>
					</li>
					<li>
						<div class="program-time">
							14:45<br>-<br>15:05
						</div>
						<div class="program-description">
							<h2>přestávka na kávu</h2>
						</div>
					</li>
					<li>
						
						<div class="program-time">
							15:05<br>-<br>15:20
						</div>
						<div class="program-description">
							<h2><strong>Lenka Benetková:</strong> „Vedení a motivace týmu“</h2>
							<p>Obchodní ředitelka divize EURO</p>
						</div>
					</li>
					<li>
						
						<div class="program-time">
							15:20<br>-<br>16:00
						</div>
						<div class="program-description">
							<h2><strong>Jiří Fabián:</strong> „Jak nastavit spravedlivé podmínky v týmu“ – 2. část</h2>
							<p>zakladatel a majitel PURPOSEFLY</p>
						</div>
					</li>
					<li>
						
						<div class="program-time">
							16:00<br>-<br>16:30
						</div>
						<div class="program-description">
							<h2>závěrečná diskuse</h2>
						</div>
					</li>
					<li>
						
						<div class="program-time">
							16:30
						</div>
						<div class="program-description">
							<h2>ukončení akce</h2>
						</div>
					</li>
				</ul>
			</div>

		</div>

		<ul class="program-nav2" style="margin-top:-1px">
			<li><a href="javascript:void(0)" id="tab1-bottom" class="active" onClick="$('#tab1-top').trigger('click');var position = $('#program').position();window.scrollTo(0,position.top - $('#main-header').height());">Dopolední blok</a></li><!--
		 -->
     <!------
     
     <li><a href="javascript:void(0)" id="tab2-bottom" onClick="$('#tab2-top').trigger('click');var position = $('#program').position();window.scrollTo(0,position.top - $('#main-header').height());">Odpolední blok</a></li>
		</ul>

	</div>
</section>
    ---->
<section id="partnerstvi">
	<div class="webwidth">
		<h1>Partnerství</h1>
		<p>Czech HR Summit by nevznikl bez strategických partnerů, kteří ví, kudy se bude ubírat další vývoj HR.<br>Děkujeme tedy všem, kdo se na zásadní události české personalistiky podíli. Děkujeme!</p>
		<img src="img/partneri-desktop-new.png" class="desktop" usemap="#partneri-desktop-map">
		<map name="partneri-desktop-map" id="partneri-desktop-map" style="width: 100%">
			<area  alt="" title="" href="http://www.ceskaposta.cz/" shape="rect" coords="59,39,326,99" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.veolia.cz/cs" shape="rect" coords="385,39,620,99" style="outline:none;" target="_blank"/>
      <area  alt="" title="" href="http://www.orea.cz/cz/" shape="rect" coords="648,16,856,124" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.euro.cz/" shape="rect" coords="1387,59,1529,107" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.sons.cz/" shape="rect" coords="1677,59,1820,107" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://okinbps.com/us/home/" shape="rect" coords="54,151,219,244" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.czechinvest.org/cz" shape="rect" coords="272,168,461,238" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.profihr.cz/" shape="rect" coords="1694,171,1852,224" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.hervis.cz/store/" shape="rect" coords="1029,167,1201,232" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.datacentrum.cz/d3/" shape="rect" coords="1262,170,1447,227" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.businessinstitut.cz/" shape="rect" coords="526,179,735,224" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.sprinx.com/" shape="rect" coords="1514,167,1641,231" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.multisport.cz/cs/" shape="rect" coords="794,178,975,220" style="outline:none;" target="_blank"/>

		</map>

		<img src="img/partneri-tablet-new.png" class="tablet" usemap="#partneri-tablet-map">
		<map name="partneri-tablet-map" id="partneri-tablet-map">
			<area  alt="" title="" href="http://www.ceskaposta.cz/" shape="rect" coords="0,0,626,220" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.veolia.cz/cs" shape="rect" coords="1393,13,1880,218" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.datacentrum.cz/" shape="rect" coords="1005,311,1395,433" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.profihr.cz/" shape="rect" coords="55,609,251,689" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.businessinstitut.cz/" shape="rect" coords="1425,597,1829,707" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="https://www.multisport.cz/cs/" shape="rect" coords="403,335,789,429" style="outline:none;" target="_blank"/>
			<area  alt="" title="" href="http://www.euro.cz/" shape="rect" coords="705,95,931,167" style="outline:none;" target="_blank"/>
      <area  alt="" title="" href="https://www.hervis.cz/store/" shape="rect" coords="405,593,607,689" style="outline:none;" target="_blank"/>
      <area  alt="" title="" href="https://okinbps.com/us/home/" shape="rect" coords="53,321,257,447" style="outline:none;" target="_blank"/>
      <area  alt="" title="" href="https://www.czechinvest.org/cz" shape="rect" coords="1601,327,1827,423" style="outline:none;" target="_blank"/>
      <area  alt="" title="" href="http://www.orea.cz/cz/" shape="rect" coords="763,587,995,717" style="outline:none;" target="_blank"/>
      <area  alt="" title="" href="http://www.sprinx.com/" shape="rect" coords="1149,603,1303,695" style="outline:none;" target="_blank"/>
      <area  alt="" title="" href="https://www.sons.cz/" shape="rect" coords="1069,91,1257,157" style="outline:none;" target="_blank"/>
		</map>
	</div>
</section>

<div class="bg-pattern-2"></div>

<section id="vstupenka">
	<div class="webwidth">
		<div class="ticket" id="buy-ticket">
			<h1>Koupit<br>vstupenku</h1>
			<br><div class="ticket-price">
				<strong>5.500 Kč</strong>
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
			<strong>HOTEL IMPERIAL</strong>
			<span>Na Poříčí 1072/15<br>Praha 1, 110 00</span>
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
				<!---<li><a href="#program">Program</a></li>--->
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