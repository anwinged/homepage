class Metrika {
    constructor(counterCb) {
        this.cb = counterCb;
    }

    get counter() {
        return this.cb.call();
    }

    hit(name) {
        if (this.counter) {
            this.counter.hit(name);
        }
    }

    goal(name, params = {}) {
        if (this.counter) {
            this.counter.reachGoal(name, params);
        }
    }
}

export default new Metrika(() => window.yaCounter41913764);
