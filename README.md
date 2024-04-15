<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>MoX (Movies and TV Shows Explorer) / FilmQuest</title>
</head>
<body>
<article>
    <header>
        <h1>
            MoX (Movies and TV Shows Explorer) / FilmQuest
        </h1>
    </header>
    <h2>Cuprins</h2>
    <ul>
        <li>
            <a href="#authors">Autori</a>
        </li>
        <li>
            <a href="#introduction">1. Introducere</a>
            <ul>
                <li><a href="#introduction-purpose">1.1 Scop</a></li>
                <li><a href="#conventions">1.2 Convenție de scriere</a></li>
                <li><a href="#audience">1.3 Publicul țintă</a></li>
                <li><a href="#product-scope">1.4 Scopul produsului</a></li>
                <li><a href="#references">1.5 Referințe</a></li>
            </ul>
        </li>
        <li><a href="#overall">2. Descriere Generală</a>
            <ul>
                <li><a href="#product-perspective">2.1 Perspectiva produsului</a></li>
                <li><a href="#product-functions">2.2 Funcțiile produsului</a></li>
                <li><a href="#users">2.3 Clase și caracteristici ale utilizatorilor</a></li>
                <li><a href="#operating-environment">2.4 Mediul de operare</a></li>
                <li><a href="#documentation">2.5 Documentația pentru utilizator</a></li>
            </ul>
        </li>
        <li><a href="#external">3. Interfețele aplicației </a>
            <ul>
                <li><a href="#user-interface">3.1 Interfața utilizatorului </a>
                    <ul>
                        <li><a href="#nav-bar">3.1.1 Bara de navigație </a></li>
                        <li><a href="#login-page">3.1.2 Pagina de autentificare </a></li>
                        <li><a href="#signup-page">3.1.3 Pagina de înregistrare </a></li>
                       
                        <li><a href="#home-page">3.1.6 Pagina de acasă </a></li>
                        <li><a href="#watchlist">3.1.7 Pagina WatchList</a></li>
                        <li><a href="#help">3.1.8 Pagina de ajutor</a></li>
                        <li><a href="#explore">3.1.9 Pagina Explore</a></li>
                        <li><a href="#about">3.1.11 Pagina informativă </a></li>
                    </ul>
                </li>
                <li><a href="#hardware-interface">3.2 Interfața Hardware </a></li>
                <li><a href="#software-interface">3.3 Interfața Software</a></li>
                <li><a href="#communication-interface">3.4 Interfața de comunicare</a></li>
            </ul>
        </li>
        <li><a href="#system-features">4. Caracteristici ale sistemului</a>
            <ul>
                <li><a href="#management">4.1 Gestionarea contului </a>
                    <ul>
                        <li><a href="#management-1">4.1.1 Descriere și generalități </a></li>
                        <li><a href="#management-2">4.1.2 Actualizarea informațiilor</a></li>
                        <li><a href="#management-3">4.1.3 Condiții de funcționare</a></li>
                    </ul>
                </li>
                <li><a href="#utilizatori">4.2 Secțiunea Utilizatori</a>
                    <ul>
                        <li><a href="#utilizatori-1">4.2.1 Descriere și generalități</a></li>
                        <li><a href="#utilizatori-2">4.2.2 Actualizarea informațiilor</a></li>
                        <li><a href="#utilizatori-3">4.2.3 Condiții de funcționare</a></li>
                    </ul>
                </li>
                <li><a href="#administrator">4.3 Secțiunea Admin</a>
                    <ul>
                        <li><a href="#administrator-1">4.3.1 Descriere și generalități</a></li>
                        <li><a href="#administrator-2">4.3.2 Actualizarea informațiilor</a></li>
                        <li><a href="#administrator-3">4.3.3 Condiții de funcționare</a></li>
                    </ul>
                </li>
                <li><a href="#logout">4.4 Secțiunea Logout</a>
                    <ul>
                        <li><a href="#logout-1">4.4.1 Descriere și generalități</a></li>
                        <li><a href="#logout-2">4.4.2 Actualizarea informațiilor</a></li>
                        <li><a href="#logout-3">4.4.3 Condiții de funcționare</a></li>
                    </ul>
                </li>
                <li><a href="#other">4.5 Alte funcționalități </a>
                    <ul>
                        <li><a href="#other-1">4.5.1 Descriere și generalități</a></li>
                        <li><a href="#other-2">4.5.2 Actualizarea informațiilor</a></li>
                        <li><a href="#other-3">4.5.3 Condiții de funcționare</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li><a href="#non-functional">5.Funcționalități pentru protecție și securitate</a>
            <ul>
                <li><a href="#safety">5.1 Protecția datelor</a></li>
            </ul>
        </li>
    </ul>
    <div role="contentinfo">
        <section id="authors" typeof="sa:AuthorsList">
            <h2>Autori</h2>
            <ul>
                <li property="schema:author" typeof="sa:ContributorRole">
            <span typeof="schema:Person">
              <meta content="Teodora" property="schema:givenName">
              <meta content="Maximiuc" property="schema:familyName">
              <span property="schema:name">Maximiuc Teodora</span>
            </span>
                <li property="schema:author" typeof="sa:ContributorRole">
            <span typeof="schema:Person">
              <meta content="Ștefania" property="schema:givenName">
              <meta content="Lăcătuș" property="schema:familyName">
              <span property="schema:name">Lăcătuș Ștefania</span>
            </span>
                </li>
            </ul>
        </section>
    </div>
    <section id="introduction">
        <h3>1. Introducere</h3>
        <section id="introduction-purpose">
            <h4>1.1 Scop</h4>
            <p>
                MoX (Movies and TV Shows Explorer)/FilmQuest este o aplicație web dezvoltată de 
                studenții menționați în secțiunea de Autori de la Facultatea de 
                Informatică a Universității Alexandru Ioan Cuza din Iași. 
                Aceasta are ca scop oferirea unei platforme interactivă și informativă 
                pentru explorarea și descoperirea diverselor filme si seriale disponibile pe serviciile de streaming Netflix și Disney.
            </p>
        </section>
        <section id="conventions">
            <h4> 1.2 Convenția documentului</h4>
            <ul>
                <li>
                    Acest document urmează șablonul de documentație a cerințelor software conform IEEE Software
                    Requirements
                    Specification.
                </li>
                <li>
                    Textul <b>îngroșat</b> este folosit pentru a defini noțiuni personalizate sau pentru a accentua
                    concepte
                    importante.
                </li>
            </ul>
        </section>
        <section id="audience">
            <h4>1.3 Publicul țintă</h4>
            <p>
                Acest document este destinat profesorilor, studenților sau dezvoltatorilor, însă orice utilizator,
                indiferent
                de cunoștințele lor tehnologice,
                poate consulta secțiunile de <b>Interfeța utilizatorului</b> și <b>Caracteristici ale sistemului</b>
                pentru a
                obține o mai bună înțelegere a ceea ce oferă aplicația.
            </p>
        </section>
        <section id="product-scope">
            <h4>1.4 Scopul Produsului</h4>
            <p>
                Scopul acestui site este de a oferi utilizatorilor o experiență captivantă și 
                personalizată în explorarea lumii filmelor și a serialelor, facilitând accesul 
                la o gamă vastă de informații relevante și variate.
            </p>
        </section>
        <section id="references">
            <h4>1.5 Bibliografie</h4>
            <ul>
                <li>Buraga Sabin-Corneliu, Site-ul Tehnologii Web, FII UAIC</li>
                <li>H Rick. IEEE-Template - GitHub</li>
            </ul>
        </section>
    </section>
    <section id="overall">
        <h3>2. Descriere Generală</h3>
        <section id="product-perspective">
            <h4>2.1 Perspectiva produsului</h4>
            <p>MoX (Movies and TV Shows Explorer)/FilmQuest
                este o aplicație dezvoltată în cadrul cursului de Tehnologii Web,
                menită să
                ofere o platformă ce permite căutarea specifică si eficientă a 
                filmului potrivit și de a ține evidența filmelor văzute sau ce urmează a fi vizionate.</p>
        </section>
        <section id="product-functions">
            <h4>2.2 Funcționalitățile produsului</h4>
            Fiecare utilizator va avea acces la urmatoarele funcționălități:
            <ul>
                <li>să se înregistreze pe site.</li>
                <li>să se autentifice pe site.</li>
                <li>să își reseteze parola in cazul in care a uitat-o.</li>
                <li>să consulte pagină "Home" și noutățile disponibile</li>
                <li>să acceseze pagina "About" pentru mai multe informații despre aplicația web</li>
                <li>să acceseze pagina "Explore" pentru a căuta filme și seriale după anumite criterii</li>
                <li>să acceseze pagina "Help" pentru a beneficia de sfaturi în vederea utilizării aplicației</li>
                <li>dacă este <b>autentificat</b>, să acceseze pagină "WatchList" și să adauge filme sau seriale în lista de vizionare</li>
                <li>dacă utilizatorul are rol de <b>admin</b>, acesta poate șterge utilizatori din baza de date</li>
                <li>dacă utilizatorul are rol de <b>admin</b>, acesta poate adăuga filme sau seriale noi</li>
                <li>dacă utilizatorul are rol de <b>admin</b>, acesta poate modifica filmele sau serialele deja existente</li>
            </ul>
        </section>
        <section id="users">
            <h4>2.3 Clase și caracteristici ale utilizatorilor</h4>
            <h5>2.3.1 Utilizator principal</h5>
            <ul>
                <li>utilizatorii autentificați pot fi:</li>
                <li style="list-style: none">
                    <ul>
                        <li>orice categorie de oameni care își doresc să caute filme sau seriale 
                            noi după criterii specifice sau care doresc să țină
                            evidența filmelor/serialelor deja vizionate sau pe care au de gând să le vizioneze.
                        </li>
                    </ul>
                </li>
                <li>
                    utilizatorii neautentificați pot fi:
                    <ul>
                        <li>orice categorie de oameni care își doresc să caute filme sau seriale 
                            noi după criterii specifice.
                        </li>
                    </ul>
                </li>
            </ul>
            <h5>2.3.2 Caracteristici</h5>
            <ul>
                <li>Utilizatorii care sunt <b> autentificați </b> pot accesa pagina "WatchList", cea de vizualizare și modificare a profilului,
                    dar și paginile ce oferă detalii și sfaturi cu privire la aplicație, "About" și "Help", pe lângă cele disponibile și 
                    utilizatorilor neautentificați.
                    Mai mult, aceștia pot să adauge filme sau seriale din pagina "Explore" și în urma căutării din pagina "Home" în lista de vizionare și să le marcheze ca fiind vizionate.
                </li>
                <li>Utilizatorii care nu sunt autentificați pot să vizualizeze și ei paginile "Home", împreună cu motorul de căutare, "About" și "Help", 
                    dar nu au acces la pagina "WatchList".
                    Așadar, aceștia pot să se înregistreze ca și utilizator și să beneficieze de toate
                    funcționalitățile.
                </li>
            </ul>
        </section>
        <section id="operating-environment">
            <h4>2.4 Mediul de operare</h4>
            <p>
                Produsul dezvoltat poate fi utilizat pe orice dispozitiv cu un browser web care suportă HTML5, CSS și
                JavaScript.
            </p>
        </section>
        <section id="documentation">
            <h4>2.5 Documentația pentru utilizator</h4>
            <p>
                Utilizatorii pot consulta acest document pentru explicații detaliate despre funcționalitățile aplicației
                web.
            </p>
        </section>
    </section>
    <section id="external">
        <h3>3. Interfețele aplicației</h3>
        <section id="user-interface">
            <h4>3.1 Interfața utilizatorului</h4>
            Mai jos, puteți vedea o prezentare generală a fiecărei pagini a aplicației și funcționalităților pe care le
            oferă:
            <ul>
                <li id="nav-bar"><b>Bara de navigație</b></li>
                <li style="list-style: none">
                    <ul>
                        <li>Aceasta reprezintă meniul de navigare către fiecare pagina a aplicației, prezent pe fiecare
                            pagină totodată.
                        </li>
                        <li class="pictures" style="list-style: none"><img alt="login" src="images/navbar.png" width=800
                        ></li>
                    </ul>
                </li>
                <li id="login-page"><b>Pagina de autentificare</b></li>
                <li style="list-style: none">
                    <ul>
                        <li>Pagina are rolul de a realiza autentificarea utilizatorilor la MoX.</li>
                        <li>Pentru a se autentifica, utilizatorul trebuie să completeze câmpurile de "username" și
                            "parolă" cu
                            credențiale <b>valide</b>, urmând să acționeze butonul <b>Sign in</b>.
                        </li>
                        <li> În cazul în care utilizatorul nu are cont pe site, acesta își poate crea unul prin
                            accesarea pagini de
                            înregistrare, ce se face prin apăsarea butonului <b>Sign Up</b>.
                        </li>
                        <li> În cazul în care utilizatorul și-a uitat parola, acesta poate să o reseteze selectând
                            opțiunea de
                            <b> Forgot your password? </b></li>
                        <li class="pictures" style="list-style: none"><img alt="login" height="400"
                                                                           src="images/login.png" width=800>
                        </li>
                    </ul>
                </li>
                <li id="signup-page"><b>Pagina de înregistrare</b></li>
                <li style="list-style: none">
                    <ul>
                        <li>Pagina oferă funcționalitatea de înregistrare a utilizatorilor, pentru a putea beneficia de
                            toate
                            funcționalitățile MoX.
                        </li>
                        <li>Pentru a se înregistra, utilizatorul trebuie să completeze câmpurile <b>Email</b>,
                            <b>First name</b>,
                            <b>Last name</b>, <b>username</b> și <b>password</b>. Mai mult, câmpurile <b>Email</b> și
                            <b>username</b>
                            trebuie să fie <b>unice</b>.
                        </li>
                        <li>În cazul în care utilizatorul își amintește că are un cont existent, acesta poate apasă
                            butonul
                            <b>Login</b> din
                            coltul din dreapta-sus
                            al paginii, pentru a reveni la meniul de autentificare.
                        </li>
                        <li class="pictures" style="list-style: none"><img alt="signup" height="400"
                                                                           src="images/signin.png" width=800>
                    </ul>
                </li>
                <li id="home-page"><b> Pagina de acasă</b></li>
                <li style="list-style: none">
                    <ul>
                        <li>Pagina are rolul de prezența ultimele noutăți, secțiunea de "About" și de a căuta rapid titluri.</li>
                        <li class="pictures" style="list-style: none"><img alt="overview" src="images/home.png"
                                                                           width=800>
                        </li>
                    </ul>
                </li>
                <li id="watchlist"><b>Pagina de WatchList</b></li>
                <li style="list-style: none">
                    <ul>
                        <li>Pagina oferă posibilitatea de a adăuga filme sau seriale în lista de vizionare, de a le marca
                            ca fiind
                            vizionate, în curs de vizionare, urmează a fi vizionate, dacă s-a renunțat la ele sau de a le șterge din listă.
                        </li>
                        <li class="pictures" style="list-style: none"><img alt="overview" src="images/watchlist.png"
                            width=800>
                        </li>
                    </ul>
                </li>
                <li id="help"><b>Pagina de Help</b></li>
                <li style="list-style: none">
                    <ul>
                        <li>Pagina conține informații despre modul de utilizare și funcționalitățiile aplicației web.</li>
                        <li class="pictures" style="list-style: none"><img alt="overview" src="images/help.png"
                                                                           width=800>
                        </li>
                    </ul>
                <li id="explore"><b>Pagina Explore</b></li>
                <li style="list-style: none">
                    <ul>
                        <li>Pagina conține cele mai populare și recent lansate filme sau seriale, sortate după genuri și anul lansării.
                            De asemenea, utilizatorii au posibilitatea de a căuta filme sau seriale după diverse criterii.
                        </li>
                        <li class="pictures" style="list-style: none"><img alt="overview" src="images/image.webp"
                                                                           width=800>
                    </ul>
                </li>
                <li id="about"><b>Pagina informativa "About"</b></li>
                <li style="list-style: none">
                    <ul>
                        <li>Pagina are rolul de a introduce site-ul MoX(FilmQuest) pe scurt, prin menționarea scopului aplicației.
                        </li>
                        <li class="pictures" style="list-style: none"><img alt="overview" src="images/about.png"
                            width=800>
                    </ul>
                </li>
            </ul>
            <section id="hardware-interface">
                <h4>3.2 Interfața Hardware</h4>
                <p>
                    Acest produs nu necesită interfețe hardware, funcționând pe orice platformă (calculatoare,
                    laptopuri,
                    telefoane etc.), care are instalată un browser.
                </p>
            </section>
            <section id="software-interface">
                <h4>3.3 Interfața Software</h4>
                <p>
                    Cerințele minime de software includ un browser funcțional, compatibil cu HTML5 și cu JavaScript.
                <h5>Postgres Database</h5>
                Aceasta reprezintă baza de date în care stocăm informații despre fiecare utilizator, filmele și serialele sale preferate.
            </section>
            <section id="communication-interface">
                <h4>3.4 Interfața de comunicare</h4>
                <p>
                    Aplicația necesită o conexiune la internet. Standardul de comunicare care va fi utilizat este HTTP.
                </p>
            </section>
            <section id="system-features">
                <h3>4. Caracteristici ale sistemului</h3>
                <section id="management">
                    <h4>4.1 Gestionarea contului</h4>
                    <h5 id="management-1">4.1.1 Descriere și generalități</h5>
                    Un utilizator se poate înregistra alegându-și un nume de utilizator, un email, o parola, numele si
                    prenumele.
                    Acesta se poate
                    autentifica având nevoie doar de numele de utilizator și de parolă.
                    <h5 id="management-2">4.1.2 Actualizarea informațiilor</h5>
                    <ul>
                        <li>
                            În momentul în care un utilizator nou este creat, credențialele acestuia sunt introduse în
                            baza de
                            date. De asemenea, când utilizatorul decide să-și modifice credențialele, noile valori sunt
                            și ele
                            actualizate în baza de date.
                        </li>
                    </ul>
                    <h5 id="management-3">4.1.3 Condiții de funcționare</h5>
                    <ul>
                        <li>
                            Pentru a-și modifica credențialele utilizatorul, trebuie să fie autentificat.
                        </li>
                        <li>
                            Pentru a se autentifica, utilizatorul are nevoie de un cont care este înregistrat în baza de
                            date.
                        </li>
                    </ul>
                </section>
                <section id="utilizatori">
                    <h4>4.2 Secțiunea de utilizatori</h4>
                    <h5 id="utilizatori-1">4.2.1 Descriere și generalități</h5>
                    Secțiunea <b>Utilizatori</b> este destinată
                    <b>adminului</b>, și aceasta îi oferă posibilitatea
                    de a vizualiza o listă cu toți utilizatorii din
                    baza de date. De asemenea, acesta are posibilitatea
                    de a elimina utilizatori din baza de date, dacă
                    dorește acest lucru.
                    <h5 id="utilizatori-2">4.2.2 Actualizarea informațiilor</h5>
                    <ul>
                        <li>
                            La apăsarea butonului de ștergere din dreptul fiecărui utilizator, credențialele
                            utilizatorului care a
                            fost selectat, sunt șterse din baza de date.
                        </li>
                    </ul>
                    <h5 id="utilizatori-3">4.2.3 Condiții de funcționare</h5>
                    <ul>
                        <li>
                            Utilizatorul trebuie să fie autentificat.
                        </li>
                        <li>
                            Utilizatorul trebuie să dețină drepturi de admin.
                        </li>
                    </ul>
                </section>
                <section id="administrator">
                    <h4>4.3 Secțiunea Admin</h4>
                    <h5 id="administrator-1">4.3.1 Descriere și generalități</h5>
                    Secțiunea <b>Admin</b> este destinată utilizatorilor ce au drepturi de <b>administrator</b> și
                    această
                    oferă facilități pe care un utilizator normal nu le are. În momentul în care adminul accesează
                    panoul de control,
                    va putea adaugă/modifică filme și seriale direct de pe platforma. Totodată, acesta este
                    capabil să șteargă
                    conturi ale utilizatorilor.
                    <h5 id="administrator-2">4.3.2 Actualizare informațiilor</h5>
                    <ul>
                        <li>
                            În momentul în care adminul adaugă un film sau serial, informațiile despre
                            acestea sunt inserate
                            în baza de
                            date.
                        </li>
                        <li>
                            În momentul în care adminul modifica un film sau serial, informațiile despre
                            acestea sunt
                            actualizate în baza de
                            date.
                        </li>
                    </ul>
                    <h5 id="administrator-3">4.3.3 Condiții de funcționare</h5>
                    <ul>
                        <li>
                            Utilizatorul trebuie să fie autentificat.
                        </li>
                        <li>
                            Utilizatorul trebuie să dețină drepturi de admin.
                        </li>
                    </ul>
                </section>
            </section>
            <section id="non-functional">
                <h3>5. Funcționalități pentru protecție și securitate</h3>
                <section id="safety">
                    <h4>5.1 Protecția datelor</h4>
                    <p>
                        Aplicația va asigura confidențialitatea datelor prin intermediul unei criptări.
                    </p>
                </section>
                
            </section>
        </section>
    </section>
</article>
</body>
</html>