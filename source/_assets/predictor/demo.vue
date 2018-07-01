<template>
    <div class="app" tabindex="0" v-on:keyup="press">
        <div v-if="isHumanWin">
            <p>
                Победа! Было очень сложно, но вы справились, поздравляю!
            </p>
            <button class="restart-button" v-on:click.prevent="restart">Заново</button>
        </div>
        <div v-else-if="isRobotWin">
            <p>
                Упс, железяка победила. Оказывается, предсказать выбор человека
                не так уж и сложно, да?
            </p>
            <button class="restart-button" v-on:click.prevent="restart">Заново</button>
        </div>
        <div v-else>
            <p class="score">
                {{ predictor.score }}
            </p>
            <p class="step">
                Ход {{ step }}
            </p>
            <div class="buttons">
                <button class="pass-button __left" value="0" v-on:click.prevent="click(0)">Нечет</button>
                <button class="pass-button __right" value="1" v-on:click.prevent="click(1)">Чет</button>
            </div>
        </div>
    </div>
</template>

<script>
import Predictor from 'predictor';
import Metrika from '../common/metrika';

const PREDICTOR_GAME_END_GOAL = 'PREDICTOR_GAME_END';
const MAX_SCORE = 50;

function make_predictor() {
    return new Predictor({
        base: 2,
        daemons: [
            { human: 3, robot: 3 },
            { human: 4, robot: 4 },
            { human: 5, robot: 5 },
            { human: 6, robot: 6 },
            { human: 8, robot: 8 },
            { human: 12, robot: 12 },
        ],
    });
}

export default {
    data() {
        return {
            predictor: make_predictor(),
        };
    },
    computed: {
        isHumanWin() {
            return this.predictor.score >= MAX_SCORE;
        },
        isRobotWin() {
            return this.predictor.score <= -MAX_SCORE;
        },
        step() {
            return this.predictor.stepCount() + 1;
        },
    },
    methods: {
        click(v) {
            const value = v ? 1 : 0;
            this.pass(value);
        },
        press(evt) {
            const value = evt.key === '1' ? 0 : 1;
            this.pass(value);
        },
        pass(value) {
            const oldScore = this.predictor.score;
            if (Math.abs(oldScore) >= MAX_SCORE) {
                return;
            }

            /* const prediction = */ this.predictor.pass(value);

            const score = this.predictor.score;
            const absScore = Math.abs(score);

            // Game over
            if (absScore === MAX_SCORE) {
                Metrika.goal(PREDICTOR_GAME_END_GOAL, {
                    winner: score > 0 ? 'human' : 'robot',
                    step_count: this.predictor.stepCount(),
                    score: score,
                });
            }
        },
        restart() {
            this.predictor = make_predictor();
        },
    },
};
</script>

<style lang="scss" scoped>
@import '../components/button';

.app {
    display: block;
    margin: 2em auto;
    padding: 2em;
    text-align: center;
    border: 1px solid transparent;

    @media (max-width: $first-media-step) {
        padding: {
            right: 0;
            left: 0;
        }
        width: auto;
    }

    &:hover {
        border-color: #ccc;
    }
}

.score {
    font-size: 400%;
    margin-top: 0.2em;
    margin-bottom: 0.2em;
}

.step {
    margin-top: 0;
    margin-bottom: 3em;
}

.buttons {
    display: flex;
    justify-content: center;
}

.restart-button {
    @extend %button;
    padding: {
        left: 1.4em;
        right: 1.4em;
    }
}

.pass-button {
    @extend %button;
    flex-grow: 0;
    min-width: 7em;
    margin: 0.2em;
}

.pass-button.__left {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.pass-button.__right {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
</style>
