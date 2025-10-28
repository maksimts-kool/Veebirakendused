let opilased = [
  { eesnimi: "Igor", perekonnanimi: "Aleksejev", sugu: "Mees", link: "https://igoraleksejev24.thkit.ee" },
  { eesnimi: "Illia", perekonnanimi: "Blahun", sugu: "Mees", link: "https://illiablahun24.thkit.ee" },
  { eesnimi: "Nikita", perekonnanimi: "Gontsarov", sugu: "Mees", link: "https://nikitagontsarov24.thkit.ee" },
  { eesnimi: "Mark", perekonnanimi: "Jürgen", sugu: "Mees", link: "https://markjurgen24.thkit.ee" },
  { eesnimi: "Nikita", perekonnanimi: "Litvinenko", sugu: "Mees", link: "https://nikitalitvinenko24.thkit.ee" },
  { eesnimi: "Marek", perekonnanimi: "Lukk", sugu: "Mees", link: "https://mareklukk24.thkit.ee" },
  { eesnimi: "Nikita", perekonnanimi: "Nikiforov", sugu: "Mees", link: "https://nikitanikiforov24.thkit.ee" },
  { eesnimi: "Nikita", perekonnanimi: "Orlenko", sugu: "Mees", link: "https://nikitaorlenko24.thkit.ee" },
  { eesnimi: "Milan", perekonnanimi: "Petrovski", sugu: "Mees", link: "https://milanpetrovski24.thkit.ee" },
  { eesnimi: "Adriana", perekonnanimi: "Pikaljov", sugu: "Naine", link: "https://adrianapikaljov24.thkit.ee" },
  { eesnimi: "Mariia", perekonnanimi: "Posvystak", sugu: "Naine", link: "https://mariiaposvystak24.thkit.ee" },
  { eesnimi: "Roman", perekonnanimi: "Prikaztsikov", sugu: "Mees", link: "https://romanprikaztsikov24.thkit.ee" },
  { eesnimi: "Artjom", perekonnanimi: "Põldsaar", sugu: "Mees", link: "https://artjompoldsaar24.thkit.ee" },
  { eesnimi: "Anastasiia", perekonnanimi: "Radasheva", sugu: "Naine", link: "https://anastasiiaradasheva24.thkit.ee" },
  { eesnimi: "Oleksandra", perekonnanimi: "Ryshniak", sugu: "Naine", link: "https://oleksandraryshniak24.thkit.ee" },
  { eesnimi: "Martin", perekonnanimi: "Rossakov", sugu: "Mees", link: "https://martinrossakov24.thkit.ee" },
  { eesnimi: "Khussein", perekonnanimi: "Takhmazov", sugu: "Mees", link: "https://khusseintakhmazov24.thkit.ee" },
  { eesnimi: "Maksim", perekonnanimi: "Tsikvasvili", sugu: "Mees", link: "https://maksimtsikvasvili24.thkit.ee" },
  { eesnimi: "Roman", perekonnanimi: "Zaitsev", sugu: "Mees", link: "https://romanzaitsev24.thkit.ee" }
];

let kont = document.getElementById("opilased");
let sisu = "";

opilased.forEach((o, i) => {
  sisu += `
    <div class="opilane" onclick="InfoF(${i})">
      ${o.eesnimi} ${o.perekonnanimi}
      <div id="info${i}" class="info" style="display:none;">
        <p><strong>Eesnimi:</strong> ${o.eesnimi}</p>
        <p><strong>Perekonnanimi:</strong> ${o.perekonnanimi}</p>
        <p><strong>Sugu:</strong> ${o.sugu}</p>
        <p><a href="${o.link}" target="_blank">Koduleht</a></p>
      </div>
    </div>
  `;
});

kont.innerHTML = sisu;

function InfoF(i) {
  let info = document.getElementById("info" + i);
  if (info.style.display == "none") {
    info.style.display = "block";
  }
  else {
    info.style.display = "none";
  }
}