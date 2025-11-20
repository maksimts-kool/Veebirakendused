let activePost = null;

function loadPost(id) {
    fetch("content/" + id + "/index.php")
        .then(res => res.text())
        .then(html => {
            document.getElementById("content-area").innerHTML = html;

            // highlight card
            if (activePost) activePost.classList.remove("selected");
            activePost = document.querySelector(`.post[onclick*="${id}"]`);
            if (activePost) activePost.classList.add("selected");

            window.scrollTo({
                top: document.getElementById("content-area").offsetTop - 20,
                behavior: "smooth"
            });
        });
}