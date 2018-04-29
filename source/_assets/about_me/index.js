import $ from 'jquery';
import _ from 'underscore';
import Backbone from 'backbone';
import Metrika from '../common/metrika';
import './style.scss'


const PageView = Backbone.View.extend({

    notes: [
        'Люблю фильм "Три идиота".',
        'Люблю кататься на велосипеде.',
        'Люблю читать фантастические книги.',
        'Люблю шоколад.',
        'На день рождения ко мне можно прийти без подарка.',
        'Не люблю пьяных людей.',
        'Предпочитаю ходить в кино на 2D-сеансы.',
        'Проехал на велосипеде 200 километров за день.',
        'Работаю программистом.',
        'Хотел бы побывать в горах.',
    ],

    shown: [],

    events: {
        'click #know-better': 'onClickKnowBetter',
    },

    initialize() {
        this.render();
    },

    render() {
        this.showRandomFact();
        return this;
    },

    pickRandomFact() {
        let available = _.difference(this.notes, this.shown);
        if (_.size(available) === 0) {
            available = this.notes;
            this.shown = [];
        }

        const phrase = _.sample(available);
        this.shown.push(phrase);

        return phrase;
    },

    showRandomFact() {
        const fact = this.pickRandomFact();
        this.$('#about-me-note').text(fact);
    },

    onClickKnowBetter(evt) {
        evt.preventDefault();
        this.showRandomFact();
        Metrika.hit(location.href);
    },
});

new PageView({ el: $('.js-about-me')});
