# TODO list
<ul>
  <li>upravovani seznamu/tabulek uvnitr detailu</li>
  <li>vybrat seznam hudebniku na koncert na zaklade zadanych skladeb</li>
  <li>moznost upravovat vazebni tabulky - uprava nastroju pro skladbu, skladeb pro koncert apod.</li>
  <li>klikatelni autori ; pri operacich(add/edit/delete) s autory zobrazovat tabulku autoru(implementace napr. pomoci cookies)</li>
  <li>datumy koncertu by mely byt pouze v budoucnosti, po uplynuti data konani by nemelo jit koncert upravovat ani odstranovat, koncert by presel do "historie" filharmonie</li>
  <li>presunout tlacitka upravit(mozna i odstranit) do detailu objektu</li>
  <li>tlacitko odstranit opatrit confirmem</li>
  <li>serazovat obsah tabulky podle domeny kliknutim na nadpis sloupce</li>
  <li>prejmenovat ttype na neco normalniho (typ nebo nastroj)</li>
  <li>grafika(, rozbalovaci tabulky)</li>
  <li>dalo by se pridat tabulku Revize(PK[datum, id_nastroje], vymeneno, poznamka) pro historii revizi nastroju</li>
  <li>mozna pridat toninu, datum vytvoreni, druh skladby</li>
  <li>vypisovat obsahy tabulek po napr. 20 radcich, dat odkazy na 1-20, 21-40, ...; v MySQL klauzule LIMIT offset, limit</li>
  <li>vytknout společné věci rolí do jednoho souboru, udelat funkce pro casto opakujici se kod</li>
  <li>narocnejsi dotazy nad databazi...? (challenge :)</li>
  <li>vypis x (napr. 10) hudebniku, kteri maji nacviceno nej(vice/mene) skladeb</li>
  <li>vypis x skladeb nejcasteji hranych na poslednich y koncertech,</li>
  <li>vypis nastroje, u kterych nebyla provadena revize od urciteho data, ...</li>
  <li>lepsi sifrovani hesel, napr. pomoci crypt()/password_hash() a password_verify()</li>
  <li>pro datumy zadat v phpMyAdminovi transformace pri prohlizeni text/plain: dateformat...?</li>
</ul>

Nejdůležitější je plánování koncertů, všechno se točí kolem toho.
Ideální funkcionalita by byla:
<ul>
  <li>Manažer zadá koncert a přiřadí mu skladby.</li>
  <li>Systém spočítá, kolik bude potřeba nástrojů.</li>
  <li>Také zkontroluje, zda je dostatečný počet hudebníků, kteří umějí hrát na dané nástroje a mají nacvičené dané skladby.</li>
  <li>Pokud jsou výše popsané podmínky splněny, systém vytvoří nový koncert.</li>
  <li>Pokud některá z výše popsaných podmínek není splněna, systém vypíše hlášení, že koncert nemůže proběhnout a co chybí k jeho realizaci</li>
  <li>Systém vypíše detail o novém koncertu (datum a čas, místo, seznam skladeb, potřebné nástroje a hudebníky)</li>
</ul>
