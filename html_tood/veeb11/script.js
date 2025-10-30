let opilased = [
  {
    nimi: "Igor Aleksejev",
    sugu: "mees",
    link: "https://igoraleksejev24.thkit.ee",
  },
  {
    nimi: "Illia Blahun",
    sugu: "mees",
    link: "https://illiablahun24.thkit.ee",
  },
  {
    nimi: "Nikita Gontsarov",
    sugu: "mees",
    link: "https://nikitagontsarov24.thkit.ee",
  },
  {
    nimi: "Mark Jürgen",
    sugu: "mees",
    link: "https://markjurgen24.thkit.ee",
  },
  {
    nimi: "Nikita Litvinenko",
    sugu: "mees",
    link: "https://nikitalitvinenko24.thkit.ee",
  },
  {
    nimi: "Marek Lukk",
    sugu: "mees",
    link: "https://mareklukk24.thkit.ee",
  },
  {
    nimi: "Nikita Nikiforov",
    sugu: "mees",
    link: "https://nikitanikiforov24.thkit.ee",
  },
  {
    nimi: "Nikita Orlenko",
    sugu: "mees",
    link: "https://nikitaorlenko24.thkit.ee",
  },
  {
    nimi: "Milan Petrovski",
    sugu: "mees",
    link: "https://milanpetrovski24.thkit.ee",
  },
  {
    nimi: "Adriana Pikaljov",
    sugu: "naine",
    link: "https://adrianapikaljov24.thkit.ee",
  },
  {
    nimi: "Mariia Posvystak",
    sugu: "naine",
    link: "https://mariiaposvystak24.thkit.ee",
  },
  {
    nimi: "Roman Prikaztsikov",
    sugu: "mees",
    link: "https://romanprikaztsikov24.thkit.ee",
  },
  {
    nimi: "Artjom Põldsaar",
    sugu: "mees",
    link: "https://artjompoldsaar24.thkit.ee",
  },
  {
    nimi: "Anastasiia Radasheva",
    sugu: "naine",
    link: "https://anastasiiaradasheva24.thkit.ee",
  },
  {
    nimi: "Oleksandra Ryshniak",
    sugu: "naine",
    link: "https://oleksandraryshniak24.thkit.ee",
  },
  {
    nimi: "Martin Rossakov",
    sugu: "mees",
    link: "https://martinrossakov24.thkit.ee",
  },
  {
    nimi: "Khussein Takhmazov",
    sugu: "mees",
    link: "https://khusseintakhmazov24.thkit.ee",
  },
  {
    nimi: "Maksim Tsikvasvili",
    sugu: "mees",
    link: "https://maksimtsikvasvili24.thkit.ee",
  },
  {
    nimi: "Roman Zaitsev",
    sugu: "mees",
    link: "https://romanzaitsev24.thkit.ee",
  },
];

let kont = document.getElementById("opilased");
let sisu = "";

opilased.forEach((o) => {
  sisu += `
    <div class="opilane ${o.sugu}" onclick="window.open('${o.link}', '_blank')">
      ${o.nimi}
    </div>
  `;
});

kont.innerHTML = sisu;