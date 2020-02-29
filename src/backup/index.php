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

$buyTicketExpiration = "12-05-2020";
$buyTicketExpired = strtotime(date("d-m-Y")) <= strtotime($buyTicketExpiration);

function email($mail, $predmet, $odesilatel, $odes_mail, $text)
{
    $sub1 = base64_encode($predmet);
    $subject = "=?utf-8?B?" . $sub1 . "?=";
    $from1 = base64_encode($odesilatel);
    $from = "=?utf-8?B?" . $from1 . "?=";
    $encoding = "8bit";
    $charset = "text/html; charset=\"utf-8\"";
    $hlavicky = "From: " . $from . " <" . $odes_mail . ">\n";
    $hlavicky .= "Content-Transfer-Encoding:" . $encoding . "\n";
    $hlavicky .= "Content-Type: " . $charset;
    mail($mail, $subject, $text, $hlavicky);
}

if (isset($_POST['buy'])) {
    if ($buyTicketExpired) {

        $alertExpired = 'Vstupenku již nelze zakoupit.';

        return;
    }

    $vsfile = fopen("vs.txt", "r");
    $vs = intval(fread($vsfile, filesize("vs.txt"))) + 1;
    $vsfile = fopen("vs.txt", "w");
    fwrite($vsfile, $vs);
    fclose($vsfile);

    if ($_POST['company']) $jmeno = $_POST['company'];
    else $jmeno = $_POST['jmeno'];

    $actualDate = new DateTime();
    $actualDatePlusSeven = date('d.m.Y', strtotime($actualDate->format('Y-m-d H:i:s') . ' +7 day'));
    $text = 'Dobrý den,<br><br>děkujeme za nákup vstupenky na Czech HR Summit.<br><br>Uhraďte prosím částku <b>' . ($_POST['qty'] * 7139) . ' Kč</b> na účet číslo <b>6657891001/5500</b>. Jako variabilní symbol zadejte <b>' . date("Y") . $vs . '</b>.<br><br>
	<br><b style="font-size:18px;">Zálohová faktura č. ' . date("Y") . $vs . '</b>
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
				' . $jmeno . '<br>
				' . $_POST['street'] . '<br>
				' . $_POST['zip'] . ', ' . $_POST['city'] . '<br>
				<br>
				<b>IČ:</b> ' . $_POST['ic'] . '<br>
				<b>DIČ:</b> ' . $_POST['dic'] . '<br>
			</td>
		</tr>
		<tr>
			<td style="border: 1px solid #000; padding:5px;">
				<b>Způsob platby:</b> Bankovní převod<br>
				<b>Variabilní symbol:</b> ' . date("Y") . $vs . '<br>
				<b>Bankovní účet:</b> 6657891001/5500
			</td>
			<td style="border: 1px solid #000; padding:5px;">
				<b>Datum vystavení:</b> ' . date('j. n. Y') . '<br>
				<b>Datum zdanitelného plnění:</b> ' . date('j. n. Y') . '<br>
				<b>Datum splatnosti:</b> ' . $actualDatePlusSeven . '
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
						<td align="right">' . round(($_POST['qty'] * 7139) / 1.21, 2) . ' Kč</td>
						<td align="right">21 %</td>
						<td align="right">' . ($_POST['qty'] * 7139) . ' Kč</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right" style="border: 1px solid #000; padding:5px; font-size:18px;">
				Celková cena s DPH: <b>' . ($_POST['qty'] * 7139) . ' Kč</b>
			</td>
		</tr>
	</table>
	<i>Toto není daňový doklad</i>

	<br><br>S přáním hezkého dne,<br>
	Tým Czech HR Summit<br>
	www.hrsummit.cz';
    email($_POST['email'], "Vstupenka - Czech HR Summit", 'Czech HR Summit', 'info@hrsummit.cz', $text);

    if ($_POST['job']) $_POST['job'] = ' - ' . $_POST['job'];

    email('info@hrsummit.cz', "Vstupenka - Czech HR Summit", $_POST['name'], $_POST['email'], '<b>Právě byl proveden nový nákup vstupenky na webu hrsummit.cz od ' . $_POST['name'] . $_POST['job'] . '.<br><br>KOPIE EMAILU ZASLANÉHO ZÁKAZNÍKOVI:<br><br></b>' . $text);

    $alert1 = 'Děkujeme za nákup vstupenky. Na vaši emailovou adresu jsme zaslali údaje k provedení platby.';
}

if (isset($_POST['send'])) {
    email('info@hrsummit.cz', "Zpráva z kontaktního formuláře hrsummit.cz", $_POST['name'], $_POST['email'], nl2br($_POST['message']));
    $alert2 = 'Zpráva byla úspěšně odeslána. Budeme se jí věnovat co nejdříve.';

}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Czech HR Summit</title>
    <link rel="shortcut icon" type="image/png" href="images/fav.png"/>
    <link href="bundle.css" rel="stylesheet">

    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date()
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0]
            a.async = 1
            a.src = g
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '../www.google-analytics.com/analytics.js', 'ga')

        ga('create', 'UA-96782855-1', 'auto')
        ga('send', 'pageview')

    </script>
</head>
<body>

<div id="black"></div>

<? if ($alert1) { ?>
    <div id="alert">
        <a href="#" id="alert-close">×</a>
        <h1>Děkujeme za nákup vstupenky</h1>
        <p>Uhraďte prosím částku <b><?= $_POST['qty'] * 7139 ?> Kč</b> na účet číslo <b>6657891001/5500</b>. Jako
            variabilní symbol zadejte <b><?= date("Y") . $vs ?></b></p>
        <p>Údaje k platbě najdete také ve své emailové schránce.</p>
        <a href="#" id="alert-button" class="buy">Zavřít</a>
    </div>
<? } ?>

<? if ($alert2) { ?>
    <div id="alert">
        <a href="#" id="alert-close">×</a>
        <h1>Zpráva byla odeslána</h1>
        <p>Vaše zpráva byl úspěšně odeslána. Budeme se jí věnovat co nejdříve.</p>
        <a href="#" id="alert-button" class="buy">Zavřít</a>
    </div>
<? } ?>

<main>

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
            <a href="#" class="scroll-to-href"><img data-src="images/logo.svg" class="lozad logo"></a>
        </div>
    </header>

    <section id="home" class="lozad" data-background-image="images/home.jpg">
        <div class="webwidth">

            <div id="intro">
                <img data-src="images/logo_odesilani.png" class="lozad" id="vertical-logo">
                <img data-src="images/slogan_odesilani.png" class="lozad" id="slogan">
            </div>

            <article id="o-summitu">
                <div id="michal"
                     onclick="document.getElementById('o-summitu').style.height = 'auto';document.getElementById('michal').style.display = 'none';"></div>
                <h1>O summitu</h1>
                <p><strong>HR Summit 2020 bude opět zásadní, provede důležitostí moderních HR nástrojů a návratu k
                        firemním hodnotám. I tentokrát bude mít summit spektakulární téma, které přitahuje pozornost
                        všech
                        profesionálů, kteří ví, že se bude dít něco velkého.</strong> Digitální transformace zdaleka
                    není
                    jen
                    o tom, poskytnout zaměstnancům k práci ty nejmodernější přístroje a technologie. Jde o propojení a
                    nahrazení časově náročných často administrativních úkonů efektivními, zautomatizovanými procesy,
                    které pracovníkům umožní soustředit se na klíčové problémy. Nový vývoj v oblasti sociálních,
                    mobilních, analytických a cloudových technologií zlepšuje vnímání zaměstnanců a kandidátů, ale také
                    umožňuje včas zpozorovat mezery v dovednostech, pomoci zaměstnancům v sebevzdělávání a dosažení
                    lepší celkové výkonnosti. Trend digitalizace je stoupající nejen v HR, ale ve všech oblastech firem.
                    Je proto potřeba posilovat u zaměstnanců, ale i u uchazečů, kompetenci využívání IT technologií.
                    Jedná se o jednu z nejdůležitějších znalostí do budoucna, a proto je vhodné podporovat i jejich
                    schopnost analytického a strategického myšlení.</p>
                <p>Český trh se v současnosti potýká s rekordně nízkou nezaměstnaností a udržet si kvalifikované a
                    talentované pracovníky se pro firmy stává prioritou. Spokojenější zaměstnanci jsou mnohem méně
                    náchylní k odchodu. Náležité peněžní ohodnocení je pro mnoho pracovníků stále na prvním místě, avšak
                    atmosféra na pracovišti, tedy firemní kultura, je pro čím dál víc lidí obdobně důležitá.</p>
                <p>Správné naladění zaměstnanců na firemní kulturu a hodnoty se pak samozřejmě projevuje nejen interně,
                    ale i externě. Uvádět firemní hodnoty a misi v reklamním sloganu a na prospektech je jedna věc,
                    ovšem ukázat zákazníkům, že to nejsou jen marketingové výkřiky, ale realita, je věc druhá.
                    Zaměstnanci znalí hodnot své firmy a řídící se jimi – nebo ještě lépe, jednající a žijící podle nich
                    – představují ideální spojnici mezi značkou a jejími zákazníky.</p>
            </article>
        </div>
    </section>

    <div class="bg-pattern-1"></div>


    <article id="profil-1" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/janKarmazin.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>Jan Karmazín<span>ředitel Odboru zaměstnanosti, GŘ ÚP ČR</span></h1>
            <p>Je absolventem University Karlovy v Praze, filozofické fakulty, obor sociologie, dále absolventem fakulty
                právnické,oboru sociální politika a personální řízení, rovněž na Karlově Universitě,v roce 2010ukončil
                studium na Masarykově universitě v Brně, fakulta právnická, studijní program Master of Public
                Administration. Svou pracovní kariéru začínal na ÚP v Benešově na pozici vedoucí oddělení
                zprostředkování zaměstnání, následně vykonával v období 2011 - 15 pozici odborného ředitele na GŘ ÚP, od
                06/2015 působí v pozici ředitele odboru zaměstnanosti.
            </p>
            <p class="profil-program"><span>Téma</span> | Aktuální situace na pracovním trhu
            </p>
        </div>
    </article>


    <article id="profil-kalousova" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/denisaKalousova.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>Denisa Kalousková<span>Strategie a rozvoj, ZPMV</span></h1>
            <p>Vzděláním klinická psycholožka pro děti a dospělé, i když původně studovala matematiku a informatiku na
                přírodovědecké fakultě. Po vysoké škole strávila skoro 15 let v technologických firmách na různých HR
                seniorních pozicích, aby po své čtyřicítce udělala krok stranou, přešla z komerčního sektoru do
                veřejnoprávní společnosti a zaměřila se více na marketing a komunikaci. V současné době má na starosti
                strategii a rozvoj v největší zaměstnanecké zdravotní pojišťovně v ČR, kde se podílí i na tvorbě
                zdravotně preventivních programů.</p>
            <p class="profil-program"><span>Téma</span> | Zdraví jako firemní hodnota</p>
        </div>
    </article>

    <article id="profil-4" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/jiriBaca.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>Jiří Báča<span>Zakladatel & CEO LutherX, a.s.</span></h1>
            <p>Progresivní vizionář s ověřenými výsledky převádění vize do reality. Jiří pokračoval ve své vášni pro
                lidi, data, zkušenosti se zákazníky a digitální technologie, které ho
                doprovázeli cestou budování griend - field mBank, jakož i v rámci nadnárodních korporací jako GE,
                Vodafone & Citi, kde zastával různé role generálního ředitele / COO / CCO.</p>

            <p class="profil-program"><span>Téma</span> | Vše o vizi, lidech, CX & Digitalu</p>
        </div>
    </article>


    <article id="profil-kluson" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/speaker-kluson-color.jpg" class="lozad">
        </div>

        <div class="profil-description">

            <h1>JAN KLUSOŇ<span>CEO ČR&amp;SR, Welcome to the Jungle</span></h1>
            <p>Jan Klusoň spoluzaložil na podzim roku 2015 první český kariérní showroom Proudly, v jehož čele stojí
                dodnes i pod vedením nového vlastníka - evropské jedničky Welcome to the Jungle. Participuje dnes jako
                CEO pro Českou republiku a Slovensko a podílí se na rozvoji značky do dalších evropských zemí. Přednáší,
                píše a mluví o všem, co souvisí s Employer Brandingem, HR marketingem a firemní kulturou, ať už na
                konferencích či v Recruitment Academy. Ve volném čase hraje basketbal, networkuje, promýšlí další rozvoj
                Welcome to the Jungle a čerpá inspiraci v zahraničí.</p>

            <p class="profil-program"><span>Téma</span> | Welcome to the Jungle</p>
        </div>
    </article>

    <article id="profil-nedbalek" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/speaker-nedbalek-color.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>CTIRAD NEDBÁLEK<span>HR Ředitel Albert ČR</span></h1>
            <p>Ctirad Nedbálek se oblasti personalistiky věnuje více než 15 let. Svou kariéru začal jako IT & Telco
                konzultant ve společnosti Synergie. Poté nastoupil do společnosti Telefónica a získával zkušenosti z
                různých rolí v rámci HR oddělení. Další dva roky zastával pozici personálního ředitele v Karlovarských
                minerálních vodách, kde měl na starosti strategii lidských zdrojů v rámci celé společnosti v České
                republice, na Slovensku a v Polsku. Od roku 2015 Ctirad působí jako HR ředitel maloobchodního řetězce
                Albert Česká republika, který je jedním z největších soukromých zaměstnavatelů v Česku. </p>

            <p class="profil-program"><span>Téma</span> | Důležitost budování značky zaměstnavatele</p>
        </div>
    </article>


    <article id="profil-toth" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/lukasToth.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>Lukáš Tóth<span>ekonom, spolumajitel výzkumné společnosti Behavio</span></h1>
            <p>Vedle PhD na University of Amsterdam před 4 lety spoluzaložil Behavio. Díky behaviorální ekonomii a
                psychologii modernizuje sociologické výzkumy. Dostává tak mnohem přesnější popis toho, co si lidé
                doopravdy myslí, jaké mají emoce a jaké podvědomé motivace je vedou k akci. Výsledkem je zlepšení
                rozhodování firem i institucí. Věnuje se mimo jiné motivacím zaměstanců - od správného měření až po
                doporučení, jak na datech založené HR kroky proměnit v motivaci zaměstnanců a snížení fluktuace. O
                lidském chování Lukáš pravidelně píše rubriku "Psycho" pro aktuálně.cz a je také spoluautorem části
                knihy Tomáše Sedláčka Ekonomie dobra a zla.</p>

            <p class="profil-program"><span>Téma</span> | Jak vyrobit srdcaře? Ukázky z dat o českých zaměstnancích</p>
        </div>
    </article>

    <article id="profil-slezak" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/speaker-slezak-color.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>PETR SLEZÁK<span>personální ředitel VEOLIA</span></h1>
            <p>Petr Slezák, ve vrcholovém řízení lidských zdrojů působí více než 20 let. V současné době působí od roku
                2003 na pozici HR Director for Continental Europe ve společnosti VEOLIA. Před tím působil v roli HR
                Director ve společnostech TESCO, HILTON či SODEXHO. Vystudoval Vysokou školu ekonomickou v Praze.</p>

            <p class="profil-program"><span>Téma</span> | Praktická personalistika vedoucí k posílení značky
                zaměstnavatele</p>
        </div>
    </article>

    <article id="profil-landa" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/petrHasek.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>Petr Hašek & Petr
                Němec<span>Specialisté v oblasti moderního HR marketingu, Entity production s.r.o</span></h1>
            <p>Marketingoví a obsahoví specialisté. Přináší řešení firmám jako je Škoda auto, Česká spořitelna, Axa,
                Orea Hotels a dalším, kterým dokáže i v současné napjaté HR situaci získávat kvalitní zaměstnance.</p>
            <p class="profil-program"><span>Téma</span> | Nejvíce přehlížený potenciál na HR trhu.
            </p>
        </div>
    </article>

    <article id="profil-lazarov" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/tomasRektor.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>Tomáš Rektor<span>zakladatel a vedoucí firmy Terapie.Info, s.r.o.</span></h1>
            <p>Absolvent lékařské fakulty, absolvoval výcvik v hlubinné psychoterapii SUR, 4 roky individuální
                sebezkušenosti v gestalt terapii, kurs neverbálních technik.
                Nyní ve výcviku v psychoanalýze, kandidát české psychoanalytické společnosti.
                V oboru působí od roku 2000, od roku 2005 pracuje v soukromé praxi, kde vede malý tým psychiatrů a
                psychologů. </p>
            <p class="profil-program"><span>Téma</span> | Jak pracovat více a nevyhořet</p>
        </div>
    </article>

    <article id="profil-zlebkova" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/lenkaZlebkova.jpg" class="lozad">
        </div>

        <div class="profil-description">

            <h1>Lenka Žlebková<span>Ředitelka kongresového centra Praha, a.s.</span></h1>
            <p>Lenka Žlebková je od března 2020 novou generální ředitelkou Kongresového centra Praha, kde od roku 2016
                zastávala pozici Obchodní a marketingové ředitelky a nastartovala posun k modernizaci největšího
                kongresového centra v ČR. Dříve působila také v Prague Convention Bureau, kde získala komplexní
                zkušenosti v oblasti rozvoje a řízení kongresové turistiky na národní úrovni. Díky svým mezinárodním
                kontaktům, které v této oblasti buduje již více jak 15 let a lidskému přístupu se jí daří budovat silný
                tým i síť spokojených klientů.</p>

            <p class="profil-program"><span>Téma</span> | Změna firemní kultury</p>
        </div>
    </article>

    <article id="profil-9" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/speaker-9-color.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>Kateřina Sadílková<span>Generální ředitelka úřadu práce ČR</span></h1>
            <p>V roce 2005 absolvovala UK FF Praha v oboru Andragogika a řízení lidských zdrojů. Na téže univerzitě
                získala v roce 2008 titul PhDr. V letech 2006 – 2011 řídila Úřad práce v Liberci. V roce 2011 byla
                jmenována první generální ředitelkou Úřadu práce ČR. Následně působila v řídících funkcích ve státní
                správě. Od roku 2013 byla ředitelkou Krajské pobočky ÚP ČR v Liberci. Od října 2014 působila rok jako
                zastupující generální ředitelka Úřadu práce ČR. Dne 2. 11. 2015 byla na základě výsledků řádného
                výběrového řízení jmenována státním tajemníkem MPSV do funkce generální ředitelky. Má zkušenosti z
                oblasti trhu práce, řízení lidských zdrojů a vzdělávání dospělých. Je předsedkyní Rady pro rozvoj
                lidských zdrojů v Libereckém kraji, předsedkyní Výkonné rady Paktu zaměstnanosti Libereckého kraje a
                členkou řady dalších komunikačních platforem v oblasti problematiky trhu práce.</p>

            <p class="profil-program"><span>Téma</span> | Situace na trhu práce</p>
        </div>
    </article>

    <article id="profil-vlcek" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/speaker-vlcek-color.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>Pavel Vlček <span>Předseda výkonného výboru PR Klub</span></h1>
            <p>Expert na oblast komunikace a vnějších vztahů, předseda Výkonného výboru oborové organizace PR Klub
                sdružující více než 120 profesionálů v oboru public relations. Pro mBank, jejímž je manažerem komunikace
                a mluvčím, získal vítězství v kategorii Interní komunikace v rámci oborové soutěže Česká cena za PR
                2018. Během své kariéry odpovídal za komunikaci například společností Citibank, Rosatom nebo
                Ministerstva průmyslu a obchodu. </p>

            <p class="profil-program"><span>Téma</span> | Důležitost komunikace při budování značky zaměstnavatele</p>
        </div>
    </article>

    <article id="profil-moderator" class="profil">
        <a href="#" class="profil-close">×</a>

        <div class="profil-photo">
            <img data-src="images/moderator-color.jpg" class="lozad">
        </div>

        <div class="profil-description">
            <h1>Jan Smetana<span>Moderátor</span></h1>
            <p>Jan Smetana je od dubna roku 2001 externím spolupracovníkem Redakce sportu ČT. Mezi jeho aktivity v ČT
                patří komentování basketbalové Euroligy mužů i žen, zápasy Mattoni NBL, ŽBL i národních týmů. V letech
                2005-2011 natáčel a moderoval pořad Time out. Od roku 2006 moderuje sportovní zprávy Studia 6 a na ČT
                24. Od roku 2012 moderuje hlavní sportovní zpravodajství ČT - Branky Body Vteřiny. V roce 2014 vyhrál
                konkurz na moderátora zábavné vědomostní soutěže Míň je víc! Na obrazovky byla soutěž uvedena v lednu
                2015.</p>

        </div>
    </article>

    <section id="prednasejici" data-background-image="images/speakers.jpg" class="lozad">
        <div class="webwidth">
            <h1>Mluvčí</h1>
            <style>
                @media (max-width: 350px), (min-width: 561px) and (max-width: 690px), (min-width: 821px) and (max-width: 1000px) {
                    .x-small {
                        display: none;
                    }
                }
            </style>
            <ul id="speakers">
                <li class="speaker">
                    <div class="photo-wrapper bg-1">
                        <div class="lozad photo" data-background-image="images/jiriBaca-color.jpg"></div>
                    </div>
                    <div class="speaker-info">
                        <h2>Jiří <span>Báča</span></h2>
                        <p>Zakladatel & CEO <br>LutherX</p>
                        <a href="#" data-id="profil-4">Zobrazit profil</a>
                    </div>
                </li>
                <li class="speaker">
                    <div class="photo-wrapper bg-2">
                        <div class="lozad photo" data-background-image="images/petrHasek-color.jpg"></div>
                    </div>
                    <div class="speaker-info">
                        <h2>Petr Hašek & <span>Petr Němec</span></h2>
                        <p>Specialisté v oblasti moderního HR marketingu<br>Entity production</p>
                        <a href="#" data-id="profil-landa">Zobrazit profil</a>
                    </div>
                </li>
                <li class="speaker">
                    <div class="photo-wrapper bg-3">
                        <div class="lozad photo" data-background-image="images/denisaKalousova-color.jpg"></div>
                    </div>
                    <div class="speaker-info">
                        <h2>Denisa <span>Kalousková</span></h2>
                        <p>strategie a rozvoj<br>ZPMV</p>
                        <a href="#" data-id="profil-kalousova">Zobrazit profil</a>
                    </div>
                </li>
                <li class="speaker">
                    <div class="photo-wrapper bg-2">
                        <div class="lozad photo" data-background-image="images/janKarmazin-color.jpg"></div>
                    </div>
                    <div class="speaker-info">
                        <h2>Jan <span>Karmazín</span></h2>
                        <p>ředitel Odboru zaměstnanosti<br>GŘ ÚP ČR</p>
                        <a href="#" data-id="profil-1">Zobrazit profil</a>
                    </div>
                </li>
                <li class="speaker">
                    <div class="photo-wrapper bg-3">
                        <div class="lozad photo" data-background-image="images/speaker-kluson.jpg"></div>
                    </div>
                    <div class="speaker-info">
                        <h2>Jan <span>Klusoň</span></h2>
                        <p>CEO ČR&amp;SR<br>Welcome to the Jungle</p>
                        <a href="#" data-id="profil-kluson">Zobrazit profil</a>
                    </div>
                </li>
                <li class="speaker">
                    <div class="photo-wrapper bg-1">
                        <div class="lozad photo" data-background-image="images/speaker-nedbalek.jpg"></div>
                    </div>
                    <div class="speaker-info">
                        <h2>Ctirad <span>Nedbálek</span></h2>
                        <p>HR Ředitel<br>Albert ČR</p>
                        <a href="#" data-id="profil-nedbalek">Zobrazit profil</a>
                    </div>
                </li>
                <li class="speaker">
                    <div class="photo-wrapper bg-1">
                        <div class="lozad photo" data-background-image="images/tomasRektor-color.jpg"></div>
                    </div>
                    <div class="speaker-info">
                        <h2>Tomáš <span>Rektor</span></h2>
                        <p>zakladatel a vedoucí firmy <br>Terapie.Info</p>
                        <a href="#" data-id="profil-lazarov">Zobrazit profil</a>
                    </div>
                </li>
                <li class="speaker">
                    <div class="photo-wrapper bg-2">
                        <div class="lozad photo" data-background-image="images/lukasToth.jpg"></div>
                    </div>
                    <div class="speaker-info">
                        <h2>Lukáš <span>Tóth</span></h2>
                        <p>ekonom a spolumajitel společnosti <br>Behavio</p>
                        <a href="#" data-id="profil-toth">Zobrazit profil</a>
                    </div>
                </li>
                <li class="speaker">
                    <div class="photo-wrapper bg-3">
                        <div class="lozad photo" data-background-image="images/lenkaZlebkova-color.jpg"></div>
                    </div>
                    <div class="speaker-info">
                        <h2>Lenka <span>Žlebková</span></h2>
                        <p>Generální ředitelka kongresového centra Praha</p>
                        <a href="#" data-id="profil-zlebkova">Zobrazit profil</a>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    <section id="program">
        <div class="webwidth">
            <h1>Program</h1>
            <div id="moderator" data-id="profil-moderator" class="program-tabs"
                 style="border: 2px solid #FFB900; cursor: pointer; margin: 60px auto; width: 50%;">
                <div class="program-time">
                    <img data-src="images/moderator.jpg" class="lozad" width="140" style="display: block;">
                </div>

                <div class="program-description">
                    <h2><strong>Jan Smetana</strong><br><span style="font-size: 20px;">Moderátor akce</span></h2>
                    <p><a href="#" style="text-transform: uppercase; color: #000;">Zobrazit profil</a></p>
                </div>
            </div>

            <ul class="program-nav">
                <li><a href="javascript:void(0)" data-tab="1" id="tab1-top"
                       class="active">Dopolední blok</a></li>

                <li><a href="javascript:void(0)" id="tab2-top"
                       data-tab="2">Odpolední blok</a></li>
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
                                <p>jednatelka a zakladatelka společnosti BeeMedia</p>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">9:30<br>–<br>10:15</div>
                            <div class="program-description">
                                <h2><strong>Ctirad Nedbálek:</strong> "Důležitost budování značky zaměstnavatele"</h2>
                                <p>HR ředitel Albert ČR</p>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">10:15<br>–<br>10:40</div>
                            <div class="program-description">
                                <h2><strong>Tomáš Rektor:</strong> "Jak pracovat více a nevyhořet"</h2>
                                <p>zakladatel a ředitel firmy Terapie.Info</p>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">10:40<br>–<br>11:00</div>
                            <div class="program-description">
                                <h2>Přestávka na kávu</h2>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">11:00<br>–<br>11:30</div>
                            <div class="program-description">
                                <h2><strong>Jan Klusoň:</strong> "Welcome to the Jungle"</h2>
                                <p>CEO ČR&SR, Welcome to the Jungle</p>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">11:30<br>–<br>12:15</div>
                            <div class="program-description">
                                <h2><strong>Petr Hašek & Petr Němec:</strong> "Nejvíce přehlížený potenciál na HR trhu"
                                </h2>
                                <p>specialisté v oblasti moderního marketingu,
                                    Entity production
                                </p>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">12:15<br>–<br>13:15</div>
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
                            <div class="program-time">13:15<br>–<br>13:30</div>
                            <div class="program-description">
                                <h2><strong>Jan Karmazín:</strong> "Aktuální situace na pracovním trhu"</h2>
                                <p>Ředitel Odboru zaměstnanosti, GŘ ÚP</p>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">13:30<br>–<br>14:15</div>
                            <div class="program-description">
                                <h2><strong>Jiří Báča:</strong> "Vše o vizi, lidech, CX & Digitalu"</h2>
                                <p>Zakladatel a CEO, LutherX</p>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">14:15<br>–<br>14:45</div>
                            <div class="program-description">
                                <h2><strong>Denisa Kalousková:</strong> "Zdraví jako firemní hodnota"</h2>
                                <p>Strategie a rozvoj, ZPMV</p>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">14:45<br>–<br>15:15</div>
                            <div class="program-description">
                                <h2>Přestávka na kávu</h2>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">15:15<br>–<br>15:45</div>
                            <div class="program-description">
                                <h2><strong>Lenka Žlebková:</strong> "Změna firemní kultury"</h2>
                                <p>Generální ředitelka, KCP </p>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">15:45<br>–<br>16:15</div>
                            <div class="program-description">
                                <h2><strong>Lukáš Toth:</strong> "Jak vyrobit srdcaře ?"</h2>
                                <p>ekonom, spolumajitel výzkumné společnosti Behavio</p>
                            </div>
                        </li>

                        <li>
                            <div class="program-time">16:15<br>–<br>16:30</div>
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
                <li><a href="javascript:void(0)" id="tab1-bottom" class="active">Dopolední blok</a></li>
                <li><a href="javascript:void(0)" id="tab2-bottom">Odpolední blok</a></li>
            </ul>

        </div>
    </section>

    <section id="partnerstvi">
        <div class="webwidth">
            <h1>Partnerství</h1>
            <p>Czech HR Summit by nevznikl bez strategických partnerů, kteří ví, kudy se bude ubírat další vývoj HR.<br>Děkujeme
                tedy všem, kdo se na zásadní události české personalistiky podíli. Děkujeme!</p>
            <div style="position: relative">
                <img data-src="images/logo-summit2.png" class="lozad desktop" usemap="#partneri-desktop-map">
                <a href="https://www.veolia.cz/cs" title="Veolia"
                   style="position: absolute; left: 0.45%; top: 72.55%; width: 8.1%; height: 17.25%; z-index: 2;"></a><a
                        href="https://www.orea.cz/" title="Orea"
                        style="position: absolute; left: 0.57%; top: 36.08%; width: 5.49%; height: 25.88%; z-index: 2;"></a><a
                        href="https://www.albert.cz/" title="Albert"
                        style="position: absolute; left: 8.49%; top: 35.29%; width: 7.64%; height: 28.24%; z-index: 2;"></a><a
                        href="https://www.zpmvcr.cz/" title="Zdravotní pojišťovna 211"
                        style="position: absolute; left: 18.23%; top: 30.98%; width: 9.8%; height: 28.24%; z-index: 2;"></a><a
                        href="https://www.formeld.com/" title="Formel D"
                        style="position: absolute; left: 18.86%; top: 69.8%; width: 4.53%; height: 21.18%; z-index: 2;"></a><a
                        href="https://www.avon.cz/" title="Avon"
                        style="position: absolute; left: 28.43%; top: 38.43%; width: 6.23%; height: 20%; z-index: 2;"></a><a
                        href="https://www.partyworld.cz/"" title="Party World" style="position: absolute; left: 24.97%;
                top: 70.59%; width: 8.61%; height: 18.43%; z-index: 2;"></a><a href="https://www.ceskaposta.cz/"
                                                                               title="Česká pošta"
                                                                               style="position: absolute; left: 35.67%; top: 38.04%; width: 7.98%; height: 21.57%; z-index: 2;"></a><a
                        href="https://euc.cz/" title="EUC"
                        style="position: absolute; left: 34.82%; top: 64.71%; width: 4.64%; height: 31.37%; z-index: 2;"></a><a
                        href="http://www.datacentrum.cz/" title="Data Centrum"
                        style="position: absolute; left: 44.51%; top: 33.33%; width: 7.98%; height: 27.06%; z-index: 2;"></a><a
                        href="https://www.urbansurvival.cz/" title="Urban Survival"
                        style="position: absolute; left: 40.49%; top: 68.24%; width: 11.27%; height: 23.53%; z-index: 2;"></a><a
                        href="https://www.hervis.cz/" title="Hervis"
                        style="position: absolute; left: 53.06%; top: 36.08%; width: 6.34%; height: 25.49%; z-index: 2;"></a><a
                        href="https://www.entita.cz/" title="Entity Production"
                        style="position: absolute; left: 52.27%; top: 69.8%; width: 6.51%; height: 20.39%; z-index: 2;"></a><a
                        href="http://www.atalian.cz/" title="Atalian"
                        style="position: absolute; left: 60.14%; top: 36.86%; width: 7.98%; height: 26.67%; z-index: 2;"></a><a
                        href="https://www.praguecc.cz/" title="Prague Congress Center"
                        style="position: absolute; left: 55.34%; top: 69.8%; width: 8.36%; height: 22.35%; z-index: 2;"></a><a
                        href="https://www.lutherone.com/" title="Lutherone"
                        style="position: absolute; left: 59.34%; top: 69.8%; width: 10.36%; height: 22.35%; z-index: 2;"></a><a
                        href="http://www.dobratiskarna.com/" title="Dobrá tiskárna"
                        style="position: absolute; left: 70.67%; top: 65.88%; width: 5.61%; height: 27.45%; z-index: 2;"></a><a
                        href="https://cuni.cz/" title="Univerzita Karlova"
                        style="position: absolute; left: 79.56%; top: 29.41%; width: 10.14%; height: 37.25%; z-index: 2;"></a><a
                        href="https://www.uradprace.cz/" title="Úřad práce ČR"
                        style="position: absolute; left: 93.49%; top: 30.2%; width: 6.34%; height: 33.73%; z-index: 2;"></a>
                <!--                <map class="partneri-desktop-map" name="partneri-desktop-map" id="partneri-desktop-map">-->
                <!--                    <area  alt="Orea" title="Orea" href="https://www.orea.cz/" shape="rect" coords="2,88,124,168" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Veolia" title="Veolia" href="https://www.veolia.cz/cs" shape="rect" coords="0,180,163,227" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Albert" title="Albert" href="https://www.albert.cz/" shape="rect" coords="149,88,286,166" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Zdravotní pojišťovna 211" title="Zdravotní pojišťovna 211" href="https://www.zpmvcr.cz/" shape="rect" coords="334,103,486,151" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Formel D" title="Formel D" href="https://www.formeld.com/" shape="rect" coords="329,174,417,235" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Avon" title="Avon" href="https://www.avon.cz/" shape="rect" coords="498,106,615,150" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Party World" title="Party World" href="https://www.partyworld.cz/" shape="rect" coords="438,181,594,232" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Česká pošta" title="Česká pošta" href="https://www.ceskaposta.cz/" shape="rect" coords="630,103,784,151" style="outline:none;" target="_self"     />-->
                <!--                    <area  alt="EUC" title="EUC" href="https://euc.cz/" shape="rect" coords="612,167,706,241" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Data Centrum" title="Data Centrum" href="http://www.datacentrum.cz/" shape="rect" coords="799,103,928,156" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Urban Survival" title="Urban Survival" href="https://www.urbansurvival.cz/" shape="rect" coords="722,174,914,234" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Hervis" title="Hervis" href="https://www.hervis.cz/" shape="rect" coords="933,101,1045,149" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Entity Production" title="Entity Production" href="https://www.entita.cz/" shape="rect" coords="926,176,1030,232" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Atalian" title="Atalian" href="http://www.atalian.cz/" shape="rect" coords="1068,90,1202,162" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Lutherone" title="Lutherone" href="https://www.lutherone.com/" shape="rect" coords="1042,176,1228,227" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Dobrá tiskárna" title="Dobrá tiskárna" href="http://www.dobratiskarna.com/" shape="rect" coords="1250,178,1352,238" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Univerzita Karlova" title="Univerzita Karlova" href="https://cuni.cz/" shape="rect" coords="1404,90,1574,161" style="outline:none;" target="_blank"     />-->
                <!--                    <area  alt="Úřad práce ČR" title="Úřad práce ČR" href="https://www.uradprace.cz/" shape="rect" coords="1657,85,1762,161" style="outline:none;" target="_blank"     />-->
                <!--                </map>-->

                <!--            <img src="logo-summit2.png" usemap="#image_map">-->
                <!--            <map name="image_map">-->
                <!--                <area alt="Orea" title="Orea" href="https://www.orea.cz/" coords="-3,-1191,121,-1115" shape="rect">-->
                <!--                <area alt="Veolia" title="Veolia" href="https://www.veolia.cz/cs" coords="2,-1098,162,-1052" shape="rect">-->
                <!--                <area alt="Albert" title="Albert" href="https://www.albert.cz/" coords="148,-1189,278,-1111" shape="rect">-->
                <!--                <area alt="Zdravotní pojišťovna 211" title="Zdravotní pojišťovna 211" href="https://www.zpmvcr.cz/" coords="315,-1191,486,-1120" shape="rect">-->
                <!--                <area alt="Formel D" title="Formel D" href="https://www.formeld.com/" coords="311,-1109,433,-1040" shape="rect">-->
                <!--                <area alt="Party World" title="Party World" href="https://www.partyworld.cz/" coords="448,-1109,582,-1038" shape="rect">-->
                <!--                <area alt="Avon" title="Avon" href="https://www.avon.cz/" coords="497,-1181,601,-1126" shape="rect">-->
                <!--                <area alt="Česká pošta" title="Česká pošta" href="https://www.ceskaposta.cz/" coords="612,-1194,777,-1125" shape="rect">-->
                <!--                <area alt="EUC" title="EUC" href="https://euc.cz/" coords="614,-1115,691,-1038" shape="rect">-->
                <!--                <area alt="Data Centrum" title="Data Centrum" href="http://www.datacentrum.cz/" coords="779,-1192,930,-1125" shape="rect">-->
                <!--                <area alt="Urban Survival" title="Urban Survival" href="https://www.urbansurvival.cz/" coords="714,-1117,923,-1043" shape="rect">-->
                <!--                <area alt="Hervis" title="Hervis" href="https://www.hervis.cz/" coords="933,-1188,1040,-1125" shape="rect">-->
                <!--                <area alt="Entity Production" title="Entity Production" href="https://www.entita.cz/" coords="925,-1103,1030,-1054" shape="rect">-->
                <!--                <area alt="Atalian" title="Atalian" href="http://www.atalian.cz/" coords="1062,-1196,1197,-1109" shape="rect">-->
                <!--                <area alt="Lutherone" title="Lutherone" href="https://www.lutherone.com/" coords="1045,-1103,1227,-1043" shape="rect">-->
                <!--                <area alt="Dobrá tiskárna" title="Dobrá tiskárna" href="http://www.dobratiskarna.com/" coords="1241,-1109,1353,-1023" shape="rect">-->
                <!--                <area alt="Univerzita Karlova" title="Univerzita Karlova" href="https://cuni.cz/" coords="1405,-1194,1589,-1100" shape="rect">-->
                <!--                <area alt="Úřad práce ČR" title="Úřad práce ČR" href="https://www.uradprace.cz/" coords="1652,-1197,1767,-1106" shape="rect">-->
                <!--            </map>-->

                <!--            <map class="partneri-desktop-map" name="partneri-desktop-map" id="partneri-desktop-map">-->
                <!--                <area alt="" title="" href="http://www.orea.cz/cz" shape="rect" coords="0,57,179,180"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="https://www.albert.cz/" shape="rect" coords="191,57,414,185"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="http://www.veolia.cz/cs" shape="rect" coords="0,192,227,274"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="https://www.avon.cz/" shape="rect" coords="474,81,646,152"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="http://www.ceskaposta.cz/" shape="rect" coords="655,86,874,152"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="http://www.datacentrum.cz/d3" shape="rect" coords="883,85,1051,152"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="https://okinbps.com/us/home" shape="rect" coords="1060,69,1191,162"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="https://www.hervis.cz/store" shape="rect" coords="480,192,631,267"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="https://www.prklub.cz/" shape="rect" coords="640,197,805,270"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="http://www.profihr.cz/" shape="rect" coords="1347,84,1490,157"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="https://www.cuni.cz/" shape="rect" coords="1571,53,1830,174"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="http://www.muvs.cvut.cz/" shape="rect" coords="1571,188,1858,274"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="https://portal.mpsv.cz/upcr" shape="rect" coords="1891,49,2081,191"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="https://www.educity.cz/" shape="rect" coords="821,186,932,274"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="https://www.hrnews.cz/" shape="rect" coords="935,185,1090,273"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--                <area alt="" title="" href="https://www.partyworld.cz/" shape="rect" coords="1091,185,1324,273"-->
                <!--                      style="outline:none;" target="_blank"/>-->
                <!--            </map>-->


                <img data-src="images/partneri-tablet2-new.png" class="lozad tablet" usemap="#partneri-tablet-map">
                <map name="partneri-tablet-map" id="partneri-tablet-map">
                    <area alt="" title="" href="http://www.orea.cz/cz" shape="rect" coords="0,53,165,181"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="https://www.albert.cz/" shape="rect" coords="203,63,419,189"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="http://www.veolia.cz/cs" shape="rect" coords="0,188,208,274"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="http://www.profihr.cz/" shape="rect" coords="0,388,141,474"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="https://www.cuni.cz/" shape="rect" coords="363,366,627,488"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="http://www.muvs.cvut.cz/" shape="rect" coords="376,488,640,610"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="https://www.avon.cz/" shape="rect" coords="465,45,638,116"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="https://www.hervis.cz/store" shape="rect" coords="459,134,632,205"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="https://www.prklub.cz/" shape="rect" coords="464,212,637,283"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="https://portal.mpsv.cz/upcr" shape="rect" coords="729,370,902,514"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="http://www.ceskaposta.cz/" shape="rect" coords="662,33,883,121"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="https://okinbps.com/us/home" shape="rect" coords="659,124,815,206"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="http://www.datacentrum.cz/d3" shape="rect" coords="914,50,1074,120"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="https://www.educity.cz/" shape="rect" coords="866,122,1026,220"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="https://www.partyworld.cz/" shape="rect" coords="831,222,1072,295"
                          style="outline:none;" target="_blank"/>
                    <area alt="" title="" href="https://www.hrnews.cz/" shape="rect" coords="656,212,816,296"
                          style="outline:none;" target="_blank"/>
                </map>
            </div>
        </div>
    </section>

    <div class="bg-pattern-2"></div>

    <section id="vstupenka">
        <div class="webwidth">
            <div class="ticket" id="buy-ticket">

                <? if ($buyTicketExpired) { ?>

                    <h1>Koupit<br>vstupenku</h1>
                    <br>
                    <div class="ticket-price">
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
                    <label for="vop"><input type="checkbox" required name="vop" id="vop"> Souhlasím s <a href="vop.pdf"
                                                                                                         target="_blank"
                                                                                                         style="color: #06AD49;">obchodními
                            podmínkami a seznámil(a) jsem se informacemi o zpracování osobních údajů.</a></label>
                </div>

                <button type="submit" class="buy" name="buy">Koupit vstupenku</button>
            </form>
        </section>
    <? } ?>

    <section id="kontakt">
        <section id="place" data-background-image="images/place.jpg" class="lozad">
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

</main>

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
            <img data-src="images/logo-yellow.svg" class="lozad logo">
        </div>

        <div id="copyright">
            <a href="http://www.beeonline.cz/" target="_blank" title="Vytvořilo webové studio BeeOnline">
                <img data-src="images/beeonline.svg" class="lozad developer">
            </a>
            <p>BeeMedia, s.r.o. &copy; 2020 Všechna práva vyhrazena</p>
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


<!--<div>

    <input type="checkbox" id="image-box__show" class="image-box__toggle" checked>

    <div class="image-box">

        <label for="image-box__show" class="image-box__close">
            <span class="icon"></span>
        </label>

        <div class="image-box__wrapper">

            <picture>

                <source srcset="img/banner_dpp.jpg" media="(max-width: 639px)">

                <img class="image-box__image" src="img/banner_dpp.jpg" alt="" style="width:501px;">

            </picture>

        </div>

    </div>
    <!-- /.image-box -- >    

</div>-->

<!--–––––––– // IMAGE-BOX // –––––––––-->


<!--<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>-->
<!--<script src="image-map-resizer/js/imageMapResizer.min.js"></script>-->
<script src="js/1.a0d25619.chunk.js"></script>
<script src="js/app.8c89b565.js"></script>


</body>
</html>