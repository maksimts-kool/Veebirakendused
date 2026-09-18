# Hajusrakenduste sõnavara kontroll

Veebileht hajusrakenduste terminite harjutamiseks kahes suunas:

| 1. veerg | 2. veerg |
|---|---|
| Kuvatakse random **eestikeelne** sõna, kontrollitakse **venekeelset** vastet | Kuvatakse random **venekeelne** sõna, kontrollitakse **eestikeelset** vastet |

## Tehnoloogiad

- HTML + puhas JavaScript (ilma raamistikuta)
- [Tailwind CSS](https://tailwindcss.com/) (browser CDN)
- [daisyUI](https://daisyui.com/): tasuta komponenditeek (card, table, alert, join, toggle, stat)
- [Lucide](https://lucide.dev/): avatud lähtekoodiga ikoonid (ISC litsents)

## Sõnavara

Terminid on võetud blogi kategooria [Hajusrakendused](https://maksimtsikvasvili24.thkit.ee/wp/category/hajusrakendused/) postitustest:

- [Hajusrakenduste Alused](https://maksimtsikvasvili24.thkit.ee/wp/hajusrakendused/2356/): hajussüsteem, tööjaotus, ressursside jagamine
- [Veebiteenused](https://maksimtsikvasvili24.thkit.ee/wp/hajusrakendused/2359/): SOAP, REST, päring, vastus, olekuta
- [Harjutused](https://maksimtsikvasvili24.thkit.ee/wp/hajusrakendused/2367/): REST API, sessionStorage/localStorage, küpsised, AJAX, GitHub API ja Actions, WebSocket, bcrypt

Kui terminil on mitu õiget vastet, eraldatakse need massiivis kaldkriipsuga (nt `хеш/хэш`); kuvatakse esimene variant.

## Käivitamine

Ava `index.html` brauseris või käivita kohalik server:

```bash
python3 -m http.server 5173
```

ja ava <http://localhost:5173>.

## Funktsionaalsus

- random sõna genereerimine massiivist (`getRandomWord`)
- kontrollkood, mis ei arvesta tühikuid, suur- ja väiketähti ega ё/е erinevust (`normalize`)
- sama loogika mõlema suuna jaoks (`setupQuiz(from, to)`)
- „Uus sõna“ nupp kummaski veerus ja „Värskenda“ nupp kogu lehe jaoks
- õigete/valede vastuste statistika
- hele ja tume teema, mobiilivaade
- animatsioonid (lehe laadimine, uus sõna, õige/vale vastus, statistika), mis lülituvad välja `prefers-reduced-motion` seadega

## Git ja LiteTracker

- `main`: algne versioon (eesti → vene)
- `88594-kahesuunaline-sonavara-tabel`: edasiarendus (kahe veeruga tabel, kujundus, kommentaarid, animatsioonid)

Haru nimi algab LiteTrackeri story ID-ga (`88594`), seega seob GitHubi veebikonks (webhook)
harude ja commit'ide muudatused automaatselt vastava story'ga. Commit'i sõnumis viidatakse
story'le kujul `[#88594]`.

- GitHubi hoidla: <https://github.com/maksimts-kool/litetracker>
- Haru: <https://github.com/maksimts-kool/litetracker/tree/88594-kahesuunaline-sonavara-tabel>
