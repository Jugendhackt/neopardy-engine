var startBoard;
var app = null;

fetch('/api/player/getBoard/1')
    .then(function (response) {
        return response.json();
    })
    .then(function (response)
    {
        console.log(response);
        response.board.forEach(el => {
            console.log(el);
        });
        startBoard = response.board;
        initVue();
    });

function initVue()
{
    app = new Vue({
        delimiters: ['[', ']'],
        el: '#app',
        data: {
            message: 'Hallo Welt',
            board: startBoard
        },
        mounted: function () {
            this.interval = setInterval(this.updateBoard, 5000);
        },
        methods: {
            updateBoard: function ()
            {
                let that = this;

                fetch('/api/player/getBoard/1')
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (response)
                    {
                        console.log(response.board[2][1].kind);
                        that.board = response.board;
                    });
            },
            btnClicken: function (btn)
            {
                console.log(btn);
                
            }
        }
    });
}



console.log('Hi');
