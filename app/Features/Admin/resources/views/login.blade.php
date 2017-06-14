<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>Animated login form</title>
    <link href='//fonts.css.network/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//cdn.staticfile.org/normalize/6.0.0/normalize.css">
    <link rel="stylesheet" href="/css/admin/login.css">

    <script src="//cdn.staticfile.org/prefixfree/1.0.7/prefixfree.min.js"></script>

</head>

<body>
<div class="wrapper">
    <form class="login">
        <p class="title">Log in</p>
        <input name="email" type="text" placeholder="用户名" autofocus/>
        <i class="fa fa-user"></i>
        <input name="password" type="password" placeholder="密码" />
        <i class="fa fa-key"></i>
        <button>
            <i class="spinner"></i>
            <span class="state">登录</span>
        </button>
    </form>
</div>
<script src="/js/admin/login.js"></script>

</body>
</html>
