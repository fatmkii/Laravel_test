<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bootstrap test</title>
    <link href="https://unpkg.com/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    [v-cloak] {
        display: none;
    }
</style>

<body>
    <div id="app" v-cloak>
        <ul class="nav">
            <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#">News</a></li>

        </ul>

        <div class="container">
            <div class="row">
                <div class="col-auto">
                    <h1>News</h1>
                    <h1>Blog</h1>
                    <h1>About</h1>
                </div>
                <div class="col-3">
                    <p>测试文段测试文段测试文段测试文段测试文段测试文段测试文段</p>
                </div>
            </div>
        </div>

        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th>Name</th>
                    <th>Age</th>
                </tr>
            </thead>
            <tr>
                <th>0</th>
                <th>Fat</th>
                <th>32</th>
            </tr>
            <tr>
                <th>1</th>
                <th>blueberry</th>
                <th>??</th>
            </tr>
        </table>


    </div>


</body>

<script src="https://unpkg.com/vue/dist/vue.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/jquery@3.3.1/dist/jquery.min.js"></script>
<script src="https://unpkg.com/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>


<script src="/js/app.js"></script>



<script>
    var app = new Vue({
        el: "#app",
        data: {
            type: 'name',
        },
        methods: {
            handleClick: function() {
                this.type = this.type === 'name' ? 'mail' : 'name'
            }
        }

    })
</script>

</html>