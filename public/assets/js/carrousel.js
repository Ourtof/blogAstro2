// partie transition et positionnement initial géré en css

const astro = [1, 2, 3, 4, 5];
const panneau = document.getElementById('panneau');
let totalImage = astro.length;
let numImage = 1;

for (let i = 0; i < astro.length; i++) {
    //console.log(astro);
    let div = document.createElement('div');
    div.classList.add('astro-img');
    let img = document.createElement('img');
    div.append(img);
    img.src = 'assets/img/home' + (i + 1) + '.jpg'
    panneau.append(div);
}

function anim() {
    panneau.style.left = '-' + (numImage * 100) + 'vw';

    // pour faire repartir les images à 0
    numImage = (numImage + 1) % 5;
}

setInterval(anim, 4000);