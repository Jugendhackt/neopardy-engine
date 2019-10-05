var startBoard;
var app = null;

fetch('/api/player/getBoard/1')
    .then(function (response) {
        return response.json();
    })
    .then(function (response) {
        console.log(response);
        response.board.forEach(el => {
            console.log(el);
        });
        startBoard = response.board;
        currentAnswer = response.currentAnswer;
        player = response.player;
        initVue();
    });

function initVue() {
    app = new Vue({
        delimiters: ['[', ']'],
        el: '#app',
        data: {
            message: 'Hallo Welt',
            board: startBoard,
            playername: '',
            debouncedNewPlayer: null,
            showAnswer: false,
            currentAnswer,
            solutionSubmitted: false,
            player
        },
        mounted: function () {
            this.interval = setInterval(this.updateBoard, 1000);
            this.debouncedNewPlayer = _.debounce(this.createNewPlayer, 500);
            if (this.currentAnswer)
            {
                this.showAnswer = true;
            }
        },
        watch: {
            playername: function () {
                this.debouncedNewPlayer(1);
            },
            currentAnswer: function () {
                console.log(this.board);
                if (this.currentAnswer)
                {
                    this.showAnswer = true;
                }
                else
                {
                    this.solutionSubmitted = false;
                    this.showAnswer = false;
                }
            }
        },
        methods: {
            updateBoard: function () {
                let that = this;

                const data = new URLSearchParams();
                data.append('playername', this.playername.toString());

                fetch('/api/player/getBoard/1',
                {
                    method: 'POST',
                    body: data
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (response) {
                        that.board = response.board;
                        that.currentAnswer = response.currentAnswer;
                        that.player = response.player;
                    });
            },
            btnClicken: function (qId) {
                const data = new URLSearchParams();
                data.append('qId', qId);

                fetch('/api/player/selectQuestion/1', {
                    method: "POST",
                    body: data
                });
            },
            createNewPlayer: function () {
                console.log('new player');
                const data = new URLSearchParams();

                data.append('playername', this.playername.toString());

                fetch('/api/player/new/1', {
                    method: "POST",
                    body: data
                })
                    .then(function (response) {
                        return response.json();
                    });
            },
            submitSolution: function ()
            {
                const data = new URLSearchParams();
                data.append('playername', this.playername.toString());

                fetch('/api/player/solutionSubmitted/1', {
                    method: "POST",
                    body: data
                });
                this.solutionSubmitted = true;
            }
        }
    });
}

