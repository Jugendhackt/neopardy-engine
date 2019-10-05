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
            debouncedNewPlayer: null
        },
        mounted: function () {
            this.interval = setInterval(this.updateBoard, 5000);
            this.debouncedNewPlayer = _.debounce(this.createNewPlayer, 200);
        },
        watch: {
            playername: function () {
                this.debouncedNewPlayer(1);
            }
        },
        methods: {
            updateBoard: function () {
                let that = this;

                fetch('/api/player/getBoard/1')
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (response) {
                        that.board = response.board;
                    });
            },
            btnClicken: function (qId) {
                fetch('/api/player/buttonPressed/1', {
                    method: "POST",
                    body: { qId, playername: this.playername }
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
            }
        }
    });
}

