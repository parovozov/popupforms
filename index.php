<?php
setcookie("mytestcooky", "Hello my frends");
$cookystr = serialize($_COOKIE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Popup with XHR and clear js except another library</title>
</head>
<body>

<style>
    *{
        padding: 0px;
        margin: 0px;
    }
    .container_popup{
        position: absolute;
        display: none;
        flex-wrap:wrap;
        width: 80%;
        height: 80%;
        justify-content: center;
        align-content: flex-start;
        top:50%;
        left: 50%;
        padding: 30px;
        border: 1px solid #999;
        border-radius: 10px;
        overflow: hidden;
        transform: translate(-50%, -50%);
        background-color: #fff;
    }
    .container_popup div{
        width: 50%;
        height: 50px;
        margin-bottom: 8px;
        border: 1px dashed #2b669a;
        border-radius: 5px;
        box-sizing: border-box;
        padding: 10px;
    }

    .container_popup input{
        width: 200px;
        height: 30px;
        padding: 0 5px 0 10px;
        border: 1px solid #BBB;
        outline: none;
    }
    .container_popup span{
        position: absolute;
        display: block;
        top: 50%;
        transform: rotateY(-50%);
        font-size: 25px;
        color: red;
        text-align: center;
    }
    #openform{
        width: 200px;
        height: 30px;
        padding: 0 5px 0 10px;
        border: 1px solid #BBB;
        outline: none;
        position: absolute;

        top:50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
<input type="button" id="openform" value="открыть форму">
<form action="request.php" name="formdata">
<div class="container_popup" id="container_popup">
    <div>Имя</div><div><input type="text" name="user" id="username" placeholder="Введите ваше имя"></div>
    <div>Email</div><div><input type="email" name="email" id="email" placeholder="Введите ваш email"></div>
    <div>Телефон</div><div><input type="tel" name="telefon" id="telefon" placeholder="Введите ваш телефон"  pattern="[0-9]+"></div>
   <input type="hidden" name="cookie" id="cookie" value="<?php echo $cookystr;?>">
    <input type="submit" value="Please press me" id="mysuperbutton" data-form-id="15" data-form-num2="23">
    <input type="button" id="closeform" value="закрыть">
</div>
</form>



<script>

    function isMobileDevice() {
        let usasg = navigator.userAgent;
        if(/Android/i.test(usasg) || /BlackBerry/i.test(usasg) || /webOS|iPhone|iPad|iPod/i.test(usasg) || /Opera Mini/i.test(usasg) || /IEMobile/i.test(usasg)){
            return true;
        }
        }

    var isMobile = {
        Android: function() {
            return /Android/i.test(navigator.userAgent);
        },
        BlackBerry: function() {
            return /BlackBerry/i.test(navigator.userAgent);
        },
        iOS: function() {
            return /webOS|iPhone|iPad|iPod/i.test(navigator.userAgent);
        },
        Opera: function() {
            return /Opera Mini/i.test(navigator.userAgent);
        },
        Windows: function() {
            let per = /IEMobile/i.test(navigator.userAgent);
            console.log(per);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };


    function closeform() {
        container = document.getElementById("container_popup");
        container.style.display='none';
    }

    function openform() {
        container = document.getElementById("container_popup");
        container.style.display='flex';
    }

    function sendForm(btn){
        let obj  = btn.dataset;
        formData = new FormData(document.forms.formdata);
        for (key in obj) {
            item = (key.indexOf('form', 0 )==0) ? key.substr(4):"";
            if(item!=''){
                formData.append(item, obj[key]);
            }
        }
        //console.log(formData);
        //console.log(`${item} = ${obj[key]}`);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'request.php');
        xhr.responseType = 'json';
        xhr.send(formData);
        xhr.onload = () => {
            console.log(xhr.response);
            if(xhr.response.err=='ok'){
                location.href = xhr.response.redirect_url;
            }
            if(xhr.response.err=='fail message'){
                conteiner = document.getElementById('container_popup');
                conteiner.innerHTML = "<span>Вася ошибочка!!!<br>Закоментируй в файле request.php " +
                    "строчку $message['err']='fail message'; и будет тебе перенавление.</span>";
            }
        }
        xhr.onerror = () => {
            alert(`Ошибка соединения`);
        }
        xhr.onprogress = function(event) {
            console.log(`Загружено ${event.loaded} из ${event.total}`);
        }
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 3) {
                console.log("загрузка");
            }
            if (xhr.readyState == 4) {
                console.log("запрос завершен");
            }
        }
    }

    function XMLrequst(){

    }

    window.onload = function () {
        if(isMobileDevice()){
            container = document.getElementById("container_popup");
            container.style.position = 'fixed';
            container.style.width = '100%';
            container.style.height = '100%';
            container.style.backgroundColor = "#777";
        }
        btn = document.getElementById("mysuperbutton");
        btnclose = document.getElementById("closeform");
        btnopen = document.getElementById("openform");
        btn.addEventListener("click", (e) => {e.stopPropagation(); e.preventDefault(); sendForm(btn)});
        btnclose.addEventListener("click", () => {closeform()});
        btnopen.addEventListener("click", () => {openform()});

    }


</script>
</body>
</html>