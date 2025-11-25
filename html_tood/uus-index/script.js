let activePost = null;

function loadPost(id) {
    fetch("content/" + id + "/index.php")
        .then(res => res.text())
        .then(html => {
            document.getElementById("content-area").innerHTML = html;

            document.getElementById("mainLogo").classList.add("shrink");

            if (activePost) activePost.classList.remove("selected");
            activePost = document.querySelector(`.post[onclick*="${id}"]`);
            if (activePost) activePost.classList.add("selected");

            window.scrollTo({
                top: document.getElementById("content-area").offsetTop - 20,
                behavior: "smooth"
            });

            const temp = document.createElement("div");
            temp.innerHTML = html;

            // Load CSS files
            temp.querySelectorAll('link[rel="stylesheet"]').forEach(oldLink => {
                const newLink = document.createElement("link");
                newLink.rel = "stylesheet";
                newLink.href = "content/" + id + "/" + oldLink.getAttribute("href");
                document.head.appendChild(newLink);
            });

            // Load JS files
            temp.querySelectorAll("script").forEach(oldScript => {
                const newScript = document.createElement("script");
                if (oldScript.src) {
                    newScript.src =
                        "content/" + id + "/" + oldScript.getAttribute("src");
                } else {
                    newScript.textContent = oldScript.textContent;
                }
                document.head.appendChild(newScript);
            });
        });
}

function filterPosts(category) {
    document.querySelectorAll(".card.post").forEach(card => {
        const c = card.getAttribute("data-category");
        card.style.display =
            category == "Kõik" || c == category ? "block" : "none";
    });
}