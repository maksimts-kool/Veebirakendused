// 1. Loo objekt raamatukogu, mille omaduseks on raamatud (massiiv raamatutest).
let raamatukogu = [
    { pealkiri: "Superaine arst", autor: "Aleksandr Kuprin", aasta: 1898 },
    { pealkiri: "Sipsik", autor: "Eno Raud", aasta: 1962 },
    { pealkiri: "451 kraadi farhengeit", autor: "Ray Breadberry", aasta: 1963 },
];

// 2. Meetod, mis kuvab kõik raamatud kenasti konsoolis
let tulemus = "";

raamatukogu.forEach((raamat) => {
    tulemus += `
    <p>
      <b>Pealkiri:</b> ${raamat.pealkiri} <br>
      <b>Autor:</b> ${raamat.autor} <br>
      <b>Aasta:</b> ${raamat.aasta}
    </p>
    <hr>
  `;
});

document.getElementById("vastus").innerHTML = tulemus;

// 3. Lisa meetod, mis lisab uue raamatu.
raamatukogu.push({ pealkiri: "Lugu", autor: "Mina", aasta: 2025 });

// 4. Lisa meetod, mis kuvab raamatukogu raamatute koguarvu.
let koguarv = raamatukogu.length;

// 5. Lisa meetod, mis arvutab, mitu raamatut on ilmunud pärast 2000. aastat.
let uuemadRaamatud = raamatukogu.filter(
    (raamat) => raamat.aasta > 2000
).length;

// 6. Koosta oma meetod ja kirjuta mida meetod tähendab
let tulemus2 = "";

raamatukogu.forEach((raamat) => {
    tulemus2 += `
    <p>
      <b>Pealkiri:</b> ${raamat.pealkiri} - ${raamat.autor} <br>
      <b>Aasta:</b> ${raamat.aasta}
    </p>
    <hr>
  `;
});

tulemus2 += `
  <p><b>Kokku raamatuid:</b> ${koguarv}</p>
  <p><b>Raamatuid pärast 2000. aastat:</b> ${uuemadRaamatud}</p>
`;

document.getElementById("vastus2").innerHTML = tulemus2;