<template>
    <section class="app">
        <p class="fact-index">Факт {{ factIndex }}</p>
        <p class="note">{{ fact }}</p>
        <button @click.prevent="next" class="button-next">
            Узнать чуть лучше
        </button>
    </section>
</template>

<script>
import _ from 'underscore';
import Metrika from '../common/metrika';

const NOTES = [
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
];

const INTERESTING_GOAL = 'INTERESTING';

export default {
    data() {
        return {
            notes: NOTES,
            shown: [],
            fact: '',
            factIndex: null,
        };
    },
    mounted() {
        this.pick();
    },
    methods: {
        next() {
            this.pick();
            Metrika.goal(INTERESTING_GOAL);
        },
        pick() {
            let available = _.difference(this.notes, this.shown);
            if (_.size(available) === 0) {
                available = this.notes;
                this.shown = [];
            }
            const fact = _.sample(available);
            this.shown.push(fact);
            this.fact = fact;
            this.factIndex = _.indexOf(NOTES, fact) + 1;
        },
    },
};
</script>

<style lang="scss" scoped>
@import '../components/button';
.app {
    text-align: center;
}

.fact-index {
    margin-top: 3em;
}

.note {
    display: block;
    font-size: 160%;
    margin: 0 auto 2em;
    min-height: 3em;
}

.button-next {
    @extend %button;
}
</style>
