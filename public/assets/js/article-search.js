const submit = document.querySelector('button[type=submit]');

submit.addEventListener("click", function(e) {
    getArticleByTag(e);
})

function getArticleByTag(e) {
    e.preventDefault();

    const tagSelect = document.querySelector('select');
    
    let data = {
        tag: tagSelect.value
    };
    
    fetch(getArticleByTagUrl, {
        method: "POST",
        body: JSON.stringify(data)
    })
        .then(res => res.json())
        .then(res => {
            const result = document.querySelector('#result');
    
            res.forEach(function(article) {
                let p = document.createElement('p');
                p.innerHTML = article.titre;
                result.append(p);
            });
        });
}