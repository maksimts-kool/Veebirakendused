# Kool ja kodu kaardil

Sama KML-fail (`andmed/kaart.kml`: kool, kodu, teekond ja maja kujund) on kuvatud kolmes lahenduses:

| Vaheleht | Lahendus | KML-i lugemine |
|---|---|---|
| `leaflet/kaart.html` | LeafletJS 1.9.4 | oma kood (`DOMParser`) |
| `openlayers/kaart.html` | OpenLayers 10.10.0 | `ol.format.KML` |
| `cesium/kaart.html` | CesiumJS 1.145.0, 3D gloobus | `Cesium.KmlDataSource` |

Töö kirjeldus ja võrdlus on lehel `vordlus.html`.

## Käivitamine

KML loetakse `fetch`'iga, seega ava leht kohaliku serveri kaudu:

```bash
python3 -m http.server 5173
```

ja ava <http://localhost:5173>.

## Git ja LiteTracker

- LiteTrackeri story: [#88717](https://eu.litetracker.com/story/show/88717) „Kolmas lahendus: sama KML-fail CesiumJS 3D gloobusel“ (7 ülesannet)
- Haru: `88717-kolmas-lahendus-cesium`

Haru nimi algab story ID-ga ja commit'i sõnumites viidatakse story'le kujul `[#88717]`,
seega seob GitHubi veebikonks (webhook) harud ja commit'id automaatselt story'ga.
