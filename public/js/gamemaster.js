var startBoard;
var app = null;
var $confirmationModal = $('#confirmationModal');

$confirmationModal.modal({
    keyboard: false,
    show: false
});

fetch('/api/gamemaster/getBoard/1')
    .then(function (response) {
        return response.json();
    })
    .then(function (response) {
        console.log(response);
        response.board.forEach(el => {
            console.log(el);
        });
        startBoard = response.board;
        currentQuestion = response.currentQuestion;
        player = response.player;
        playerQueue = response.playerQueue;
        players = response.players;
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
            solutionSubmitted: false,
            player,
            currentQuestion,
            playerQueue,
            currentPlayer: null,
            players
        },
        mounted: function () {
            this.interval = setInterval(this.updateBoard, 1000);
            this.debouncedNewPlayer = _.debounce(this.createNewPlayer, 500);
            if (this.currentQuestion)
            {
                this.showAnswer = true;
            }
        },
        watch: {
            playername: function () {
                this.debouncedNewPlayer(1);
            },
            currentQuestion: function () {
                console.log(this.board);
                if (this.currentQuestion)
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

                fetch('/api/gamemaster/getBoard/1',
                {
                    method: 'POST',
                    body: data
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (response) {
                        that.board = response.board;
                        that.currentQuestion = response.currentQuestion;
                        that.player = response.player;
                        that.playerQueue = response.playerQueue;
                        that.players = response.players;
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
            },
            loadModal: function ()
            {

            },
            correct: function ()
            {
                console.log('correct');
                this.voteOnPlayer(1);
            },
            incorrect: function ()
            {
                this.voteOnPlayer(0);
            },
            voteOnPlayer: function (correct)
            {
                const data = new URLSearchParams();
                data.append('player', this.currentPlayer.id);
                data.append('correct', correct);
                console.log(correct);
                var that = this;

                fetch('/api/gamemaster/voteOnPlayer/', {
                    method: "POST",
                    body: data
                })
                .then(function (response) {
                    return response.json();
                })
                .then(function (response) {
                    that.solutionSubmitted = true;
                    that.currentPlayer = response;
                    that.closeModal();
                });
            },
            reset: function ()
            {
                fetch('/api/gamemaster/resetGame/1');
            },
            openModal: function ()
            {
                $('#confirmationModal').modal('show');
            },
            closeModal: function ()
            {
                $('#confirmationModal').modal('hide');
            },
            onPlayerClick: function (gpId)
            {
                const data = new URLSearchParams();
                data.append('player', gpId);
                var that = this;

                fetch('/api/gamemaster/getPlayer/', {
                    method: "POST",
                    body: data
                })
                .then(function (response) {
                    return response.json();
                })
                .then(function (response) {
                    that.solutionSubmitted = true;
                    that.currentPlayer = response;
                    that.openModal();
                });
            }
        }
    });
}

