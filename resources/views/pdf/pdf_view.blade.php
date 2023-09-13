<html>

<head>
    <title>Laravel Create & Download Pdf Tutorial</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link href="//db.onlinewebfonts.com/c/c56d4721c0ed78e62202b4430d0a955d?family=Asmelina+Harley" rel="stylesheet" type="text/css" />

</head>
<style>
    @import url(//db.onlinewebfonts.com/c/c56d4721c0ed78e62202b4430d0a955d?family=Asmelina+Harley);

    @font-face {
        font-family: "Asmelina Harley";
        src: url("//db.onlinewebfonts.com/t/c56d4721c0ed78e62202b4430d0a955d.eot");
        src: url("//db.onlinewebfonts.com/t/c56d4721c0ed78e62202b4430d0a955d.eot?#iefix") format("embedded-opentype"), url("//db.onlinewebfonts.com/t/c56d4721c0ed78e62202b4430d0a955d.woff2") format("woff2"), url("//db.onlinewebfonts.com/t/c56d4721c0ed78e62202b4430d0a955d.woff") format("woff"), url("//db.onlinewebfonts.com/t/c56d4721c0ed78e62202b4430d0a955d.ttf") format("truetype"), url("//db.onlinewebfonts.com/t/c56d4721c0ed78e62202b4430d0a955d.svg#Asmelina Harley") format("svg");
    }

    th {
        font-family: "Asmelina Harley";
    }
</style>

<body>
    <div class="container">
        <br />
        <a href="{{ route('pdfview',['download'=>'pdf']) }}" class="btn btn-primary">Download PDF</a>
        <table class="table table-bordered">
            <tr>
                <th>Sr.No</th>
                <th>Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Model</th>
                <th>Sku</th>
            </tr>
            
        </table>
    </div>

</body>

</html>