require('../../../../../../resources/assets/js/app');


let working = false;
$('.login').on('submit', function(e) {
    e.preventDefault();
    if (working) {
        return;
    }

    working = true;
    let $this = $(this), $state = $this.find('button > .state');

    $this.addClass('loading');
    $state.html('登录中...');

    const data = $this.serializeJSON();

    axios.post('./login', data).then((ret) => {
        const msg = ret.data;
        if (msg.code === 0) {
            $this.addClass('ok');
            $state.html(`${msg.data.name}, 欢迎回来!`);

            setTimeout(function() {
                working = false;
                location.replace('/admin/#');
            }, 600);
        } else {
            $state.html('登录');
            $this.removeClass('ok loading');
            working = false;

            apperror(msg.msg);
            $('[name=email]', $this).focus();
        }
    }).catch((err) => {
        const resp = err.response;
        if (resp && resp.status === 422) {
            for (let k in resp.data) {
                for (let i in resp.data[k]) {
                    apperror(resp.data[k][i]);
                }
            }
        }

        $state.html('登录');
        $this.removeClass('ok loading');
        working = false;
        $('[name=email]', $this).focus();
    });
});