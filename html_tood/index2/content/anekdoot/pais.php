<link rel="stylesheet" href="style.css">
<header>
    <h1>Värske teade</h1>
    <nav>
        <ul>
            <li><a onclick="postForm(event, 'content/anekdoot/index.php')">Avaleht</a></li>
            <li><a onclick="postForm(event, 'content/anekdoot/anekdoot1.php')">Anekdoot Nädal 1</a></li>
            <li><a onclick="postForm(event, 'content/anekdoot/anekdoot2.php')">Anekdoot Nädal 2</a></li>
        </ul>
    </nav>
</header>
<script>
        function postForm(e, url) {
            e.preventDefault();
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.getElementById("content-area").innerHTML = html;
                })
                .catch(err => console.error("Viga sisu laadimisel: ", err));
        }
    </script>