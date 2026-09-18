// Sõnavara massiiv: iga objekt sisaldab hajusrakenduste termini eesti keeles (et) ja vene keeles (ru).
// Terminid on võetud blogi kategooria "Hajusrakendused" postitustest:
// https://maksimtsikvasvili24.thkit.ee/wp/category/hajusrakendused/
// Kui terminil on mitu õiget vastet, eraldatakse need kaldkriipsuga (nt "хеш/хэш")
const words = [
  // Postitus "Hajusrakenduste Alused"
  { et: "hajussüsteem", ru: "распределённая система" },
  { et: "tööjaotus", ru: "разделение труда" },
  { et: "ressursside jagamine", ru: "совместное использование ресурсов" },
  { et: "korduvkasutamine", ru: "повторное использование" },

  // Postitus "Veebiteenused" (SOAP, REST, HTTP)
  { et: "veebiteenus", ru: "веб-сервис/веб-служба" },
  { et: "päring", ru: "запрос" },
  { et: "vastus", ru: "ответ" },
  { et: "ressurss", ru: "ресурс" },
  { et: "olekuta", ru: "без сохранения состояния" },
  { et: "sõnum", ru: "сообщение" },
  { et: "päis", ru: "заголовок" },
  { et: "veahaldus", ru: "обработка ошибок" },
  { et: "jõudlus", ru: "производительность" },
  { et: "privaatvõrk", ru: "частная сеть" },
  { et: "standard", ru: "стандарт" },

  // Postitus "Harjutused": REST API, andmete kuvamine ja veebisalvestus
  { et: "andmebaas", ru: "база данных" },
  { et: "filtreerimine", ru: "фильтрация" },
  { et: "sorteerimine", ru: "сортировка" },
  { et: "massiiv", ru: "массив" },
  { et: "andmevorming", ru: "формат данных" },
  { et: "sessioon", ru: "сессия/сеанс" },
  { et: "vahekaart", ru: "вкладка" },
  { et: "võti", ru: "ключ" },
  { et: "väärtus", ru: "значение" },
  { et: "küpsis", ru: "куки/cookie" },
  { et: "kasutaja tuvastamine", ru: "идентификация пользователя" },

  // Postitus "Harjutused": AJAX, GitHub API ja GitHub Actions
  { et: "asünkroonne", ru: "асинхронный" },
  { et: "sünkroonne", ru: "синхронный" },
  { et: "andmevahetus", ru: "обмен данными" },
  { et: "liides", ru: "интерфейс" },
  { et: "kasutajanimi", ru: "имя пользователя" },
  { et: "hoidla/repositoorium", ru: "репозиторий" },
  { et: "töövoog", ru: "рабочий процесс" },
  { et: "haru", ru: "ветка" },

  // Postitus "Harjutused": vestlusruum (WebSocket, WebHook)
  { et: "server", ru: "сервер" },
  { et: "klientrakendus", ru: "клиентское приложение" },
  { et: "sündmus", ru: "событие" },
  { et: "sündmuspõhine", ru: "событийно-ориентированный" },
  { et: "ühendus", ru: "соединение" },
  { et: "vestlusruum", ru: "чат" },
  { et: "reaalajaline", ru: "в реальном времени" },
  { et: "kahesuunaline", ru: "двусторонний" },
  { et: "kohtvõrk", ru: "локальная сеть" },
  { et: "teek", ru: "библиотека" },
  { et: "pakett", ru: "пакет" },

  // Postitus "Harjutused": turvaline räsi (bcrypt)
  { et: "parool", ru: "пароль" },
  { et: "räsi", ru: "хеш/хэш" },
  { et: "räsimine", ru: "хеширование/хэширование" },
  { et: "sool", ru: "соль" },
  { et: "rünnak", ru: "атака" },
  { et: "lihttekst", ru: "открытый текст" }
];

// Ühine statistika mõlema veeru jaoks – loendurid on väljaspool setupQuiz funktsiooni, et neid jagada
const stats = { correct: 0, wrong: 0 };

// Valib massiivist juhusliku sõna – Math.random() annab arvu 0..1, mis korrutatakse massiivi pikkusega.
// Kui "exclude" on antud, valitakse uuesti seni, kuni saadakse eelmisest erinev sõna (ei korda sama sõna järjest)
function getRandomWord(exclude) {
  let word;
  do {
    word = words[Math.floor(Math.random() * words.length)];
  } while (word === exclude && words.length > 1);
  return word;
}

// Normaliseerib vastuse: eemaldab tühikud, teeb väiketähtedeks ja asendab "ё" tähega "е",
// et kasutaja ei saaks vale vastust ainult suurtähe või ё/е erinevuse pärast
function normalize(text) {
  return text.trim().toLowerCase().replace(/ё/g, "е");
}

// Tükeldab massiivi välja aktsepteeritud vasteteks; esimest varianti kuvatakse küsitava sõnana
function variants(value) {
  return value.split("/").map(function (v) { return v.trim(); });
}

// Käivitab CSS animatsiooni uuesti: eemaldab eelmise animatsiooniklassi, sunnib brauseri reflow'd
// (offsetWidth lugemine) ja lisab klassi tagasi – muidu sama klassi korduv lisamine animatsiooni ei käivitaks
function animate(el, className) {
  el.classList.remove("anim-word", "anim-pop", "anim-shake", "anim-bump", "anim-celebrate", "anim-spin");
  void el.offsetWidth;
  el.classList.add(className);
}

// Tulemuse tüübile vastav Lucide ikoon (avatud lähtekoodiga ikoonikomplekt)
const resultIcons = { success: "circle-check", error: "circle-x", warning: "triangle-alert" };

// Kuvab tulemuse daisyUI "alert" komponendina; tüüp määrab värvi ja ikooni (success / error / warning).
// Tekst lisatakse textContent kaudu, et sisestatud väärtus ei saaks HTML-ina käivituda
function showResult(el, type, message) {
  el.className = "alert alert-soft alert-" + type + " mt-4";
  el.innerHTML = '<i data-lucide="' + resultIcons[type] + '" class="size-5 shrink-0"></i>';
  const text = document.createElement("span");
  text.textContent = message;
  el.appendChild(text);
  lucide.createIcons();
  el.hidden = false;
  animate(el, "anim-pop");
}

// Uuendab statistika plokki; täpsus arvutatakse protsentides ja ümardatakse täisarvuks.
// Muutunud loendur ja täpsus "hüppavad" korraks suuremaks, et muutus oleks märgatav
function renderStats(changedId) {
  const total = stats.correct + stats.wrong;
  document.getElementById("stat-correct").textContent = stats.correct;
  document.getElementById("stat-wrong").textContent = stats.wrong;
  document.getElementById("stat-accuracy").textContent =
    total === 0 ? "–" : Math.round((stats.correct / total) * 100) + "%";
  animate(document.getElementById(changedId), "anim-bump");
  animate(document.getElementById("stat-accuracy"), "anim-bump");
}

// Seob ühe tabeli veeru loogika: "from" on kuvatava sõna keel, "to" on keel, milles vastust kontrollitakse.
// Sama funktsioon teenindab mõlemat suunda, seega pole vaja koodi dubleerida
function setupQuiz(from, to) {
  const wordEl = document.getElementById("word-" + from);
  const answerEl = document.getElementById("answer-" + from);
  const resultEl = document.getElementById("result-" + from);
  let currentWord = null;
  let answered = false; // statistikasse läheb ainult esimene kontroll iga sõna kohta

  // Kuvab veergu uue random sõna ja tühjendab eelmise vastuse ning tulemuse
  function nextWord() {
    currentWord = getRandomWord(currentWord);
    answered = false;
    wordEl.textContent = variants(currentWord[from])[0];
    answerEl.value = "";
    resultEl.hidden = true;
    animate(wordEl, "anim-word");
  }

  // Kontrollkood: võrdleb kasutaja vastust massiivis oleva õige tõlkega sihtkeeles
  function checkAnswer() {
    const userAnswer = normalize(answerEl.value);

    // Tühja vastuse korral ei loeta seda veaks, vaid palutakse midagi sisestada
    if (userAnswer === "") {
      showResult(resultEl, "warning", "Palun sisesta vastus.");
      animate(answerEl.parentElement, "anim-shake");
      answerEl.focus();
      return;
    }

    // Vastus on õige, kui see ühtib ükskõik millise aktsepteeritud variandiga
    const accepted = variants(currentWord[to]);
    const isCorrect = accepted.some(function (v) { return normalize(v) === userAnswer; });

    // Loendurit suurendatakse ainult esimesel katsel, et sama sõna korduv kontroll ei moonutaks statistikat
    if (!answered) {
      isCorrect ? stats.correct++ : stats.wrong++;
      answered = true;
      renderStats(isCorrect ? "stat-correct" : "stat-wrong");
    }

    if (isCorrect) {
      showResult(resultEl, "success", "Õige!");
      animate(wordEl, "anim-celebrate");
    } else {
      showResult(resultEl, "error", "Vale. Õige vastus on: " + accepted.join(" / "));
      animate(answerEl.parentElement, "anim-shake"); // vale vastuse korral sisestusväli "raputab"
    }
  }

  document.getElementById("check-" + from).addEventListener("click", checkAnswer);
  document.getElementById("next-" + from).addEventListener("click", function () {
    const icon = this.querySelector("svg");
    if (icon) animate(icon, "anim-spin");
    nextWord();
    answerEl.focus();
  });
  answerEl.addEventListener("keydown", function (event) {
    if (event.key === "Enter") checkAnswer();
  });

  nextWord();
}

// 1. veerg: eesti sõna → venekeelne vaste; 2. veerg: vene sõna → eestikeelne vaste
setupQuiz("et", "ru");
setupQuiz("ru", "et");

// Asendab kõik lehel olevad <i data-lucide="..."> elemendid SVG ikoonidega
lucide.createIcons();

// Värskendamise nupp laeb lehe uuesti, mis genereerib mõlemasse veergu uue random sõna
// Enne uuesti laadimist teeb ikoon ühe pöörde; kui animatsioonid on välja lülitatud, laaditakse kohe
document.getElementById("refreshBtn").addEventListener("click", function () {
  const icon = this.querySelector("svg");
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (icon) animate(icon, "anim-spin");
  setTimeout(function () { location.reload(); }, reduceMotion ? 0 : 450);
});
