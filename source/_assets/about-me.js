(function() {
    'use strict';

    var notes = [
        'Любимый фильм "Три идиота".',
        'Люблю кататься на велосипеде.',
        'Люблю читать фантастические книги.',
        'Люблю шоколад.',
        'На день рождения ко мне можно прийти без подарка.',
        'Не люблю пьяных людей.',
        'Предпочитаю ходить в кино на 2D-сеансы.',
        'Проехал на велосипеде 200 километров за день.',
        'Работаю программистом.',
        'Хотел бы побывать в горах.',
    ];

    function selectRandomNote() {
        return notes[Math.floor(Math.random() * notes.length)];
    }

    function updateNode() {
        var el = document.getElementById('about-me-note');
        if (el) {
            el.innerHTML = selectRandomNote();        
        }
    }

    function onKnowBetter(event) {
        event.preventDefault();
        if (window.yaCounter41913764) {
            window.yaCounter41913764.hit(location.href);
        }
        updateNode();
    }

    window.addEventListener('DOMContentLoaded', updateNode);

    var el = document.getElementById('know-better');
    if (el) {
        el.addEventListener('click', onKnowBetter);
    }

}());
