<?
session_start();

/*if (!isset($_GET["dev"]) && !preg_match("/^\/?index.php/", $_SERVER["PHP_SELF"])) {
    
    header("Location: /");
    
    exit;
}*/

$C_videoBoxVideoShown = isset($_COOKIE["video-shown"]) && $_COOKIE["video-shown"];

if (!$C_videoBoxVideoShown) {

    setcookie("video-shown", "1", strtotime("tomorrow 02:00"));
}

$buyTicketExpiration = "06-05-2019";
$buyTicketExpired = strtotime(date("d-m-Y")) <= strtotime($buyTicketExpiration);

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
    if ($buyTicketExpired) {
        
        $alertExpired = 'Vstupenku již nelze zakoupit.';

        return;
    }
    
	$vsfile=fopen("vs.txt", "r");
	$vs = intval(fread($vsfile, filesize("vs.txt"))) + 1;
	$vsfile=fopen("vs.txt", "w");
	fwrite($vsfile, $vs);
	fclose($vsfile);

	if($_POST['company']) $jmeno = $_POST['company'];
	else $jmeno = $_POST['jmeno'];
  
  $actualDate = new DateTime();
  $actualDatePlusSeven = date('d.m.Y', strtotime($actualDate->format('Y-m-d H:i:s') . ' +7 day'));
	$text = 'Dobrý den,<br><br>děkujeme za nákup vstupenky na Czech HR Summit.<br><br>Uhraďte prosím částku <b>'.($_POST['qty'] * 7139).' Kč</b> na účet číslo <b>6657891001/5500</b>. Jako variabilní symbol zadejte <b>'.date("Y").$vs.'</b>.<br><br>
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
						<td>Vstupenka - Czech HR Summit</td>
						<td align="right">1 ks</td>
						<td align="right">'.round( ($_POST['qty'] * 7139) / 1.21, 2 ).' Kč</td>
						<td align="right">21 %</td>
						<td align="right">'.($_POST['qty'] * 7139).' Kč</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right" style="border: 1px solid #000; padding:5px; font-size:18px;">
				Celková cena s DPH: <b>'.($_POST['qty'] * 7139).' Kč</b>
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
        <p>Uhraďte prosím částku <b><?= $_POST['qty'] * 7139 ?> Kč</b> na účet číslo <b>6657891001/5500</b>. Jako variabilní symbol zadejte <b><?= date("Y") . $vs ?></b></p>
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

<? if($alertExpired) { ?>
	<div id="alert">
		<a href="#" id="alert-close">×</a>
		<h1>Vstupenku již nelze zakoupit</h1>
        <p>Vstupenku bylo možné zakoupit pouze do <?= $buyTicketExpiration; ?>.</p>
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
			<img src="img/logo_odesilani.png" id="vertical-logo">
			<img src="img/slogan_odesilani.png" id="slogan">
		</div>

		<article id="o-summitu">
            <div id="michal" onclick="document.getElementById('o-summitu').style.height = 'auto';document.getElementById('michal').style.display = 'none';document.getElementById('o-summitu').style.marginBottom = '-60px';"></div>
			<h1>O summitu</h1>  
            <p>HR Summit 2019 bude opět zásadní, provede tématem budování značky zaměstnavatele. Atraktivní značka zaměstnavatele pomáhá. Budování značky zaměstnavatele (Employer Branding) spočívá v systematickém vytváření a sdílení pozitivní zaměstnanecké zkušenosti. Hlavním nástrojem je promyšlená personální komunikace se současnými, budoucími i bývalými zaměstnanci. Employer Branding je dlouhodobý a nepřetržitý proces.</p>
            <p>Značka zaměstnavatele vzniká v myslích lidí, kteří ve firmě pracují, pracovali a nebo teprve pracovat chtějí. Tvoří ji myšlenky, pocity a očekávání získané díky předchozí zkušenosti s konkrétní firmou v roli zaměstnavatele. Pokud je tato zkušenost pozitivní, je značka zaměstnavatele vnímána jako atraktivní. Atraktivní značka zaměstnavatele přitahuje ty správné lidi. A správní lidé na správném místě dělají tu nejlepší práci...</p>
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
        <h1>ADAM DIGRIN <span>strategický poradce, customer experience designer</span></h1>
        <p>Po více než 15 let se pohybuje v oblasti strategického marketingu, vytváření zákaznické zkušenosti a změn systémů myšlení organizací či jednotlivců. Působil na strategických i kreativních pozicích v několika komunikačních agenturách, založil a po čtyři roky vedl institut marketingové komunikace určený profesionálům v reklamě. V posledních pěti letech se věnuje poradenství, systemickému koučování a doprovázení organizací při změnách komunikační strategie, vývoji nových služeb či systémů organizace. Mezi poslední partnery, se kterými spolupracoval, patří například společnosti Honeywell a E.O.N. Věří v princip minimalismu, sílu mytologie a příběhů v našem myšlení a intuitivní formy řízení. </p>
        <p class="profil-program"><span>Téma</span> | Kmen a mýtus - zapomenuté pojmy v dnešním světě a organizacích či skrytá působící síla, kterou nevidíme?   </p>
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

        <p class="profil-program"><span>Téma</span> | Dodáme Vám řešení</p>
	</div>
</article>


<article id="profil-nedbalek" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-nedbalek-color.jpg">
	</div>

	<div class="profil-description">
        <h1>CTIRAD NEDBÁLEK<span>HR Ředitel Albert ČR</span></h1>
        <p>Ctirad Nedbálek se oblasti personalistiky věnuje více než 15 let. Svou kariéru začal jako IT & Telco konzultant ve společnosti Synergie. Poté nastoupil do společnosti Telefónica a získával zkušenosti z různých rolí v rámci HR oddělení. Další dva roky zastával pozici personálního ředitele v Karlovarských minerálních vodách, kde měl na starosti strategii lidských zdrojů v rámci celé společnosti v České republice, na Slovensku a v Polsku. Od roku 2015 Ctirad působí jako HR ředitel maloobchodního řetězce Albert Česká republika, který je jedním z největších soukromých zaměstnavatelů v Česku. </p>

        <p class="profil-program"><span>Téma</span> | Důležitost budování značky zaměstnavatele</p>
	</div>
</article>

<article id="profil-slezak" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-slezak-color.jpg">
	</div>

	<div class="profil-description">
        <h1>PETR SLEZÁK<span>personální ředitel VEOLIA</span></h1>
        <p>Petr Slezák, ve vrcholovém řízení lidských zdrojů působí více než 20 let. V současné době působí od roku 2003 na pozici HR Director for Continental Europe ve společnosti VEOLIA. Před tím působil v roli HR Director ve společnostech TESCO, HILTON či SODEXHO. Vystudoval Vysokou školu ekonomickou v Praze.</p>

        <p class="profil-program"><span>Téma</span> | Praktická personalistika vedoucí k posílení značky zaměstnavatele</p>
	</div>
</article>

<article id="profil-landa" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-landa-color.jpg">
	</div>

	<div class="profil-description">
        <h1>JIŘÍ LANDA<span>Partner &amp; Employer Brand Strategist ve společnosti BrandBakers</span></h1>
        <p>Jiří je zakládajícím partnerem BrandBakers, společnosti, která patří mezi první průkopníky Employer Brandingu v České republice. Podílí se na výzkumech, vytváří strategie a realizuje komunikaci firem s vlastními zaměstnanci i kandidáty na trhu práce s primárním cílem učinit danou společnost pro lidi srozumitelnou a odlišit ji na trhu práce. S cílem získat pro téma značky zaměstnavatele mladé tváře také učí na pražské VŠE. Za svou více než sedmiletou kariéru v Employer Brandingu spolupracoval přibližně s padesátkou společností z nejrůznějších odvětví byznysu. Sází na opravdovost. Jeho krédo je „Hlavně žádné lakování na růžovo. Kandidát stejně brzy zjistí, jak to u nás ve firmě funguje.“</p>

        <p class="profil-program"><span>Téma</span> | Chcete značku zaměstnavatele? Tak ukažte, kdo skutečně jste.</p>
	</div>
</article>

<article id="profil-lazarov" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-lazarov-color.jpg">
	</div>

	<div class="profil-description">
        <h1>GORJAN LAZAROV<span>Generální ředitel Orea Hotels & Resorts</span></h1>
        <p>Gorjan je generálním ředitelem hotelového řetězce Orea Hotels & Resorts, pod který spadá 12 hotelů. Svou pracovní kariéru začínal ve finančním oddělení hotelu Marriott a v průběhu času se vypracoval až po zkušeného hotelového ředitele Boscolo Prague Hotel a nebo Boscolo Milano Hotel. Předtím prošel řadou odborných a manažerských pozic ve společnostech Vodafone a Marriott. Mezi jeho klíčové dovednosti patří strategie, zákaznická zkušenost a vyhledávání inovativních způsobů a jejich zavádění do byznysu. Rychle se učí a snadno se přizpůsobuje v různých průmyslových odvětvích. Gorjan vystudoval University of Pittsburgh, Katz Business School. Ve volném čase rád hraje tenis, lyžuje nebo sleduje basketbal.</p>

        <p class="profil-program"><span>Téma</span> | Nová Orea</p>
	</div>
</article>

<article id="profil-kluson" class="profil">
	<a href="#" class="profil-close">×</a>

	<div class="profil-photo">
		<img src="img/speaker-kluson-color.jpg">
	</div>

	<div class="profil-description">

        <h1>JAN KLUSOŇ<span>spluzakladatel prvního českého kariérního showroomu Proudly</span></h1>
        <p>Jan Klusoň spoluzaložil na podzim roku 2015 první český kariérní showroom Proudly, v jehož čele stojí dodnes i pod vedením nového vlastníka - evropské jedničky Welcome to the Jungle. Přednáší, píše a mluví o všem, co souvisí s Employer Brandingem, HR marketingem a firemní kulturou, ať už na konferencích, v Recruitment Academy nebo ve svém YouTube pořadu EB Minute. Ve volném čase hraje basketbal, networkuje, promýšlí další rozvoj Proudly a čerpá inspiraci v zahraničí.</p>

        <p class="profil-program"><span>Téma</span> | Stvořili jsme  kariérní  showroom</p>
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

        <p class="profil-program"><span>Téma</span> | Situace na trhu práce</p>
    </div>
</article>

<article id="profil-vlcek" class="profil">
    <a href="#" class="profil-close">×</a>

    <div class="profil-photo">
        <img src="img/speaker-vlcek-color.jpg">
    </div>

    <div class="profil-description">
        <h1>Pavel Vlček <span>Předseda výkonného výboru PR Klub</span></h1>
        <p>Expert na oblast komunikace a vnějších vztahů, předseda Výkonného výboru oborové organizace PR Klub sdružující více než 120 profesionálů v oboru public relations. Pro mBank, jejímž je manažerem komunikace a mluvčím,  získal vítězství v kategorii Interní komunikace v rámci oborové soutěže Česká cena za PR 2018. Během své kariéry odpovídal za komunikaci například společností  Citibank, Rosatom nebo Ministerstva průmyslu a obchodu.  </p>

        <p class="profil-program"><span>Téma</span> | Důležitost komunikace při budování značky zaměstnavatele</p>
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
        <style>
            @media (max-width: 350px), (min-width: 561px) and (max-width: 690px), (min-width: 821px) and (max-width: 1000px) {
                .x-small { display: none; }
            }
        </style>
		<ul id="speakers">
            <li class="speaker">
                <div class="photo-wrapper bg-1">
                    <div class="photo" style="background-image: url('img/speaker-1.jpg');"></div>	
                </div>
                <div class="speaker-info">
                    <h2>Adam <span>Digrin</span></h2>
                    <p>strategický poradce, customer experience designer</p>
                    <a href="#" data-id="profil-1">Zobrazit profil</a>
                </div>
            </li>
            <li class="speaker">
                <div class="photo-wrapper bg-2">
                    <div class="photo" style="background-image: url('img/speaker-kluson.jpg');"></div>	
                </div>
                <div class="speaker-info">
                    <h2>Jan <span>Klusoň</span></h2>
                    <p>spluzakladatel prvního českého kariérního showroomu Proudly</p>
                    <a href="#" data-id="profil-kluson">Zobrazit profil</a>
                </div>
            </li>
            <li class="speaker">
                <div class="photo-wrapper bg-1">
                    <div class="photo" style="background-image: url('img/speaker-landa.jpg');"></div>	
                </div>
                <div class="speaker-info">
                    <h2>Jiří <span>Landa</span></h2>
                    <p>Partner &amp; Employer Brand Strategist ve společnosti BrandBakers </p>
                    <a href="#" data-id="profil-landa">Zobrazit profil</a>
                </div>
            </li>
            <li class="speaker">
                <div class="photo-wrapper bg-2">
                    <div class="photo" style="background-image: url('img/speaker-lazarov.jpg');"></div>	
                </div>
                <div class="speaker-info">
                    <h2>Gorjan <span>Lazarov</span></h2>
                    <p>Generální ředitel Orea Hotels &amp; Resorts</p>
                    <a href="#" data-id="profil-lazarov">Zobrazit profil</a>
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
                <div class="photo-wrapper bg-2">
                    <div class="photo" style="background-image: url('img/speaker-nedbalek.jpg');"></div>	
                </div>
                <div class="speaker-info">
                    <h2>Ctirad <span>Nedbálek</span></h2>
                    <p><span class="x-small">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> HR Ředitel Albert ČR <span class="x-small">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                    <a href="#" data-id="profil-nedbalek">Zobrazit profil</a>
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
                    <div class="photo" style="background-image: url('img/speaker-slezak.jpg');;"></div>	
                </div>
                <div class="speaker-info">
                    <h2>Petr <span>Slezák</span></h2>
                    <p><span class="x-small">&nbsp; </span>personální ředitel VEOLIA<span class="x-small"> &nbsp;</span></p>
                    <a href="#" data-id="profil-slezak">Zobrazit profil</a>
                </div>
            </li>
            <li class="speaker">
                <div class="photo-wrapper bg-1">
                    <div class="photo" style="background-image: url('img/speaker-vlcek.jpg');;"></div>	
                </div>
                <div class="speaker-info">
                    <h2>Pavel <span>Vlček</span></h2>
                    <p>Předseda výkonného výboru PR Klub</p>
                    <a href="#" data-id="profil-vlcek">Zobrazit profil</a>
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
			<li><a href="javascript:void(0)" data-tab="1" id="tab1-top" onClick="$('#tab1-bottom').addClass('active');$('#tab2-bottom').removeClass('active');" class="active">Dopolední blok</a></li><!--
		 --><li><a href="javascript:void(0)" id="tab2-top" onClick="$('#tab2-bottom').addClass('active');$('#tab1-bottom').removeClass('active');" data-tab="2">Odpolední blok</a></li>
		</ul>

        <div class="program-tabs">

            <div class="program-tab" data-tab="1">

                <ul>

                    <li>
                        <div class="program-time">8:30<br>–<br>9:30</div>
                        <div class="program-description">
                            <h2><strong>Registrace &amp; Networking</strong></h2>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">9:30</div>
                        <div class="program-description">
                            <h2><strong>Šárka Fričová: </strong> Zahájení konference</h2>
                            <p>jednatelka a zakladatelka společnosti BeeMedia, s.r.o.</p>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">9:30<br>–<br>9:55	</div>
                        <div class="program-description">
                            <h2><strong>Adam Digrín:</strong> "Kmen a mýtus – zapomenuté pojmy v dnešním světě a organizacích či skrytá působící síla, kterou nevidíme?"</h2>
                            <p>strategický poradce, customer experience designer</p>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">9:55<br>–<br>10:15</div>
                        <div class="program-description">
                            <h2><strong>Petr Lucký:</strong> "Dodáme Vám řešení"</h2>
                            <p>výkonný ředitel DataCentrum systems &amp; consulting, a.s.</p>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">10:15<br>–<br>10:30</div>
                        <div class="program-description">
                            <h2><strong>Kateřina Sadílková:</strong> "Situace na trhu práce"</h2>
                            <p>generální ředitelka ÚP ČR</p>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">10:30<br>–<br>11:00</div>
                        <div class="program-description">
                            <h2>Přestávka na kávu</h2>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">11:00<br>–<br>11:45</div>
                        <div class="program-description">
                            <h2><strong>Jiří Landa:</strong> "Chcete značku zaměstnavatele? Tak ukažte kdo skutečně jste."</h2>
                            <p>partner &amp; employer brand strategist BrandBakers</p>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">11:45<br>–<br>12:30</div>
                        <div class="program-description">
                            <h2><strong>Jan Klusoň:</strong> "Stvořili jsme kariérní showroom"</h2>
                            <p>spoluzakladatel prvního českého kariérního showroomu Proudly</p>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">12:30<br>–<br>13:30</div>
                        <div class="program-description">
                            <h2>Společný oběd</h2>
                            <p>restaurant Café Imperial (salonek Topas)</p>
                        </div>
                    </li>

                </ul>

            </div>

            <div class="program-tab" data-tab="2">

                <ul>

                    <li>
                        <div class="program-time">13:30<br>–<br>14:10</div>
                        <div class="program-description">
                            <h2><strong>Ctirad Nedbálek:</strong> "Důležitost budování značky zaměstnavatele"</h2>
                            <p>HR ředitel Albert  ČR</p>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">14:10<br>–<br>14:30</div>
                        <div class="program-description">
                            <h2><strong>Petr Slezák:</strong> "Praktická personalistika vedoucí k posílení značky zaměstnavatele"</h2>
                            <p>personální ředitel CEE VEOLIA</p>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">14:30<br>–<br>15:00</div>
                        <div class="program-description">
                            <h2>Přestávka na kávu</h2>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">15:00<br>–<br>15:40</div>
                        <div class="program-description">
                            <h2><strong>Gorjan Lazarov:</strong> "Nová OREA"</h2>
                            <p>generální ředitel OREA Hotels &amp; Resorts </p>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">15:40<br>–<br>16:00</div>
                        <div class="program-description">
                            <h2><strong>Pavel Vlček:</strong> "Důležitost komunikace při budování značky zaměstnavatele"</h2>
                            <p>předseda výkonného výboru PR klub</p>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">16:00<br>–<br>16:30</div>
                        <div class="program-description">
                            <h2><strong>Závěrečná diskuse</strong></h2>
                        </div>
                    </li>

                    <li>
                        <div class="program-time">16:30</div>
                        <div class="program-description">
                            <h2><strong>Tombola a ukončení akce</strong></h2>
                        </div>
                    </li>

                </ul>

            </div>

        </div>
        
		<ul class="program-nav2">
			<li><a href="javascript:void(0)" id="tab1-bottom" class="active" onClick="$('#tab1-top').trigger('click');var position = $('#program').position();window.scrollTo(0,position.top - $('#main-header').height());">Dopolední blok</a></li><!--
    --><li><a href="javascript:void(0)" id="tab2-bottom" onClick="$('#tab2-top').trigger('click');var position = $('#program').position();window.scrollTo(0,position.top - $('#main-header').height());">Odpolední blok</a></li>
		</ul>

	</div>
</section>

<section id="partnerstvi">
    <div class="webwidth">
        <h1>Partnerství</h1>
        <p>Czech HR Summit by nevznikl bez strategických partnerů, kteří ví, kudy se bude ubírat další vývoj HR.<br>Děkujeme tedy všem, kdo se na zásadní události české personalistiky podíli. Děkujeme!</p>
        <img src="img/logo-summit2.png" class="desktop" usemap="#partneri-desktop-map">

        <map class="partneri-desktop-map" name="partneri-desktop-map" id="partneri-desktop-map">
            <area  alt="" title="" href="http://www.orea.cz/cz" shape="rect" coords="0,57,179,180" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.albert.cz/" shape="rect" coords="191,57,414,185" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="http://www.veolia.cz/cs" shape="rect" coords="0,192,227,274" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.avon.cz" shape="rect" coords="474,81,646,152" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="http://www.ceskaposta.cz" shape="rect" coords="655,86,874,152" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="http://www.datacentrum.cz/d3" shape="rect" coords="883,85,1051,152" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://okinbps.com/us/home" shape="rect" coords="1060,69,1191,162" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.hervis.cz/store" shape="rect" coords="480,192,631,267" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.prklub.cz" shape="rect" coords="640,197,805,270" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="http://www.profihr.cz" shape="rect" coords="1347,84,1490,157" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.cuni.cz" shape="rect" coords="1571,53,1830,174" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="http://www.muvs.cvut.cz" shape="rect" coords="1571,188,1858,274" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://portal.mpsv.cz/upcr" shape="rect" coords="1891,49,2081,191" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.educity.cz/" shape="rect" coords="821,186,932,274" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.hrnews.cz/" shape="rect" coords="935,185,1090,273" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.partyworld.cz/" shape="rect" coords="1091,185,1324,273" style="outline:none;" target="_blank"     />
        </map>


        <img src="img/partneri-tablet2-new.png" class="tablet" usemap="#partneri-tablet-map">
        <map name="partneri-tablet-map" id="partneri-tablet-map">
            <area  alt="" title="" href="http://www.orea.cz/cz" shape="rect" coords="0,53,165,181" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.albert.cz" shape="rect" coords="203,63,419,189" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="http://www.veolia.cz/cs" shape="rect" coords="0,188,208,274" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="http://www.profihr.cz" shape="rect" coords="0,388,141,474" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.cuni.cz" shape="rect" coords="363,366,627,488" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="http://www.muvs.cvut.cz" shape="rect" coords="376,488,640,610" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.avon.cz" shape="rect" coords="465,45,638,116" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.hervis.cz/store" shape="rect" coords="459,134,632,205" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.prklub.cz" shape="rect" coords="464,212,637,283" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://portal.mpsv.cz/upcr" shape="rect" coords="729,370,902,514" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="http://www.ceskaposta.cz" shape="rect" coords="662,33,883,121" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://okinbps.com/us/home" shape="rect" coords="659,124,815,206" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="http://www.datacentrum.cz/d3" shape="rect" coords="914,50,1074,120" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.educity.cz" shape="rect" coords="866,122,1026,220" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.partyworld.cz" shape="rect" coords="831,222,1072,295" style="outline:none;" target="_blank"     />
            <area  alt="" title="" href="https://www.hrnews.cz" shape="rect" coords="656,212,816,296" style="outline:none;" target="_blank"     />
        </map>
    </div>
</section>

<div class="bg-pattern-2"></div>

<section id="vstupenka">
	<div class="webwidth">
		<div class="ticket" id="buy-ticket">
           
            <? if ($buyTicketExpired) { ?>
            
                <h1>Koupit<br>vstupenku</h1>
                <br><div class="ticket-price">
                    <strong>5.900 Kč</strong>
                    <span>bez DPH</span>
                </div>				
                <a href="#" class="buy" id="buy-ticket-button">Koupit</a>
            
			<? } else { ?>
            
                <h1>Vstupenku již nelze zakoupit</h1>
            
			<? } ?>
		</div>
	</div>
</section>

<? if ($buyTicketExpired) { ?>
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
			<label for="vop"><input type="checkbox" required name="vop" id="vop"> Souhlasím s <a href="vop.pdf" target="_blank" style="color: #06AD49;">obchodními podmínkami a seznámil(a) jsem se informacemi o zpracování osobních údajů.</a></label>
		</div>

		<button type="submit" class="buy" name="buy">Koupit vstupenku</button>
	</form>
</section>
<? } ?>

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


<!--–––––––– ** VIDEO-BOX ** –––––––––-->


<!--<div>-->

    <!--<input type="checkbox" id="video-box__show" class="video-box__toggle" <? echo $C_videoBoxVideoShown ? "" : "checked"; ?>>-->

    <!--<div class="video-box">-->

        <!--<label for="video-box__show" class="video-box__close">-->
            <!--<span class="icon"></span>-->
        <!--</label>-->

        <!--<div class="video-box__wrapper">-->
            <!--<iframe class="video-box__video" src="https://player.vimeo.com/video/272720502?muted=1&<? echo $C_videoBoxVideoShown ? "" : "autoplay=1"; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>-->
        <!--</div>-->

    <!--</div>-->
    <!-- /.video-box -->    

<!--</div>-->
    
    
<!--–––––––– // VIDEO-BOX // –––––––––-->


<!--–––––––– ** IMAGE-BOX ** –––––––––-->


<!--<div>-->

    <!--<input type="checkbox" id="image-box__show" class="image-box__toggle" checked>-->

    <!--<div class="image-box">-->

        <!--<label for="image-box__show" class="image-box__close">-->
            <!--<span class="icon"></span>-->
        <!--</label>-->

        <!--<div class="image-box__wrapper">-->

            <!--<picture>-->

                <!--<source srcset="img/new-web-banner-mobile.jpg" media="(max-width: 639px)">-->

                <!--<img class="image-box__image" src="img/new-web-banner.jpg" alt="Brzy pro vás spustíme dový web akce - HR Summit 15. 5. 2019, Hotel Imperial, Praha 1">-->

            <!--</picture>-->

        <!--</div>-->

    <!--</div>-->
    <!-- /.image-box -->    

<!--</div>-->
    
    
<!--–––––––– // IMAGE-BOX // –––––––––-->


<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="image-map-resizer/js/imageMapResizer.min.js"></script>
<script src="main.js?2"></script>


</body>
</html>