<?php
if(!empty($_FILES['chunk']))
{
    $chunk = $_FILES['chunk'];
    $file = fopen($chunk['name'], 'a');
    fwrite($file, file_get_contents($chunk['tmp_name']));
    fclose($file);
    if($_POST['lastchunk'] === 'true'){
        //security
        if($_POST['originalname'] === 'index.php'){
            return;
        }
        $finalfilename = $_POST['originalname'];
        if(file_exists($finalfilename)){
            $finalfilename = time().'_'.$finalfilename;
        }
        rename($chunk['name'], $finalfilename);
        echo 'https://'.$_SERVER['HTTP_HOST'].'/'.$finalfilename;
    }
    return;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="Service d'upload de fichier">
    <link rel="shortcut icon" type="image/x-icon" href="data:image/x-icon;,">
    <title>DropIt</title>
    <style>
        * { margin: 0;padding: 0;box-sizing: border-box; }
        :root{--rainbow-color: #9cd04b;}
        body{
            display: flex;
            flex-direction: column;
            width: 100vw;
            height: 100vh;
            justify-content: center;
            align-items: center;
            background-color: #0e0e0e;
            opacity: 1;
            background-image:  linear-gradient(135deg, #9cd04b 25%, transparent 25%), linear-gradient(225deg, #9cd04b 25%, transparent 25%), linear-gradient(45deg, #9cd04b 25%, transparent 25%), linear-gradient(315deg, #9cd04b 25%, #0e0e0e 25%);
            background-position:  24px 0, 24px 0, 0 0, 0 0;
            background-size: 48px 48px;
            background-repeat: repeat;
            overflow: hidden;
            font-family: sans-serif;
        }
        .card{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 24px;
            background-color: #fff;
            gap: 12px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-height: 80vh;
        }
        form{
            display: flex;
            gap: 10px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            overflow: hidden;
        }
        .gradient-text{
            background: linear-gradient(90deg, #0e0e0e, var(--rainbow-color), #0e0e0e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            -moz-background-clip: text;
            -moz-text-fill-color: transparent;
            background-clip: text;
            background-color: #0e0e0e;
            background-size: 200%;
            animation: rainbow 1s infinite linear;
        }
        input[type="file"]{
            display: none;
        }
        label[for="uploaded_file"]{
            cursor: pointer;
            font-size: 20px;
            padding: 12px 24px;
            border: #0e0e0e 2px solid;
        }
        #file-list{
            display: flex;
            flex-direction: column;
            align-items: start;
            gap: 12px;
            margin-top: 12px;
            width: 100%;
            overflow-x: hidden;
            overflow-y: auto;
        }
        .file-label{
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            justify-content: space-between;
        }
        .file-label svg{
            flex-basis: 16px;
            flex-shrink: 0;
        }
        .file-label .file-name{
            font-size: 16px;
            color: #0e0e0e;
            text-overflow: ellipsis;
            text-wrap: nowrap;
            flex-grow: 1;
            overflow: hidden;
        }
        .file-label .file-ctrl{
            flex-shrink: 0;
            display: flex;
            flex-basis: 86px;
            align-items: baseline;
        }
        .file-label .file-ctrl a{
            text-decoration: none;
        }
        footer{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 50px;
            background-color: #0e0e0e;
            color: #fff;
            font-size: 12px;
            position: absolute;
            bottom: 0;
        }

        @media screen and (width <= 400px){
            .card, main{
                width: 100%;
            }
        }

        @keyframes rainbow {
            0%{background-position: 0%}
            100%{background-position: -200%}
        }
    </style>
</head>
<body ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
    <main >
        <div class="card">
            <h1 class="gradient-text">DROP'IT</h1>
            <form action="index.php" method="post" enctype="multipart/form-data">
                <input type="file" name="uploaded_file" id="uploaded_file" multiple>
                <label for="uploaded_file">Choose a file or drag it...</label>
                <div id="file-list"></div>
            </form>
        </div>
    </main>

    <footer>
        <div>Made with ❤️ by @rzm</div>
    </footer>

    <!-- Favicon -->
    <script>
        var favIcon = "PHN2ZyBmaWxsPSIjOWNkMDRiIiBoZWlnaHQ9IjgwMCIgd2lkdGg9IjgwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgMzc0LjEgMzc0LjEiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxwYXRoIGQ9Ik0zNDQgMjA4Yy0xNyAwLTMwIDEzLTMwIDMwdjc2SDYwdi03NmEzMCAzMCAwIDAgMC02MCAwdjEwNmMwIDE3IDEzIDMwIDMwIDMwaDMxNGMxNyAwIDMwLTEzIDMwLTMwVjIzOGMwLTE3LTEzLTMwLTMwLTMweiIvPjxwYXRoIGQ9Im0xMjQgMTM2IDMzLTM0djExMmEzMCAzMCAwIDAgMCA2MCAwVjEwMmwzNCAzNGEzMCAzMCAwIDAgMCA0MiAwYzEyLTEyIDEyLTMxIDAtNDNMMjA4IDlhMzAgMzAgMCAwIDAtNDIgMEw4MSA5M2EzMCAzMCAwIDEgMCA0MyA0M3oiLz48L3N2Zz4=";
        var docHead = document.getElementsByTagName('head')[0];
        var newLink = document.createElement('link');
        newLink.rel = 'shortcut icon';
        newLink.href = 'data:image/svg+xml;base64,'+favIcon;
        docHead.appendChild(newLink);
    </script>
    <!-- Form -->
    <script>
        let filesArray = [];
        let fileTpl = new Object({
            name: '',
            size: 0,
            progress: 0,
            chunks: [],
            domFile: null,
            fails: 0,
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[type="file"]').addEventListener('change', function(e) {
                for (let i = 0; i < e.target.files.length; i++) {
                    let file = e.target.files[i];
                    let fileObj = Object.create(fileTpl);
                    fileObj.name = file.name;
                    fileObj.size = file.size;
                    fileObj.chunks = spliIt(file);
                    fileObj.domFile = tplIt(file.name);
                    document.querySelector('#file-list').appendChild(fileObj.domFile);
                    sendIt(fileObj);
                }
            })
        });

        const sendIt = (file) => {
            let xhr = new XMLHttpRequest();
            let formData = new FormData();
            formData.append('chunk', file.chunks[0], `${file.name}.part`);
            formData.append('lastchunk', file.chunks.length === 1);
            formData.append('originalname', file.name);
            xhr.open('post', 'index.php', true);
            xhr.send(formData);
            xhr.onload = function(e) {
                file.fails = 0;
                file.progress += file.chunks.shift().size;
                file.domFile.querySelector('.file-ctrl').innerHTML = `${Math.floor((file.progress / file.size) * 100)}%`;
                if(file.chunks.length > 0){
                    sendIt(file);
                }else{
                    finishIt(file, xhr.responseText);
                }
            };
            xhr.onerror = function(e) {
                file.fails++;
                if(file.fails < 3){
                    sendIt(file);
                }else{
                    file.domFile.querySelector('.file-ctrl').innerHTML += '❌';
                }
            };
        }

        const finishIt = (file, link) => {
            file.domFile.querySelector('.file-ctrl').innerHTML += '✅';
            file.domFile.querySelector('.file-ctrl').innerHTML += `<a href="${link}" target="_blank">🔗</a>`;
        }

        const spliIt = (file) => {
            let chunkSize = 1024 * 1024 * 5;
            let chunks = [];
            let fileSize = file.size;
            let offset = 0;
            let chunk = null;
            while (offset < fileSize) {
                chunk = file.slice(offset, offset + chunkSize);
                chunks.push(chunk);
                offset += chunkSize;
            }
            return chunks;
        }

        const tplIt = (name) => {
            let domFileTpl = document.createElement('div');
            domFileTpl.classList.add('file-label');
            let randomColor = Math.floor(Math.random()*16777215).toString(16);
            domFileTpl.style.setProperty('--rainbow-color', '#'+randomColor);
            let fileSVG = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            fileSVG.setAttribute('height', '16');
            fileSVG.setAttribute('width', '16');
            fileSVG.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
            fileSVG.setAttribute('viewBox', '0 0 317 317');
            fileSVG.setAttribute('xml:space', 'preserve');
            let fileSVGPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            fileSVGPath.setAttribute('d', 'M271 71 212 4c-2-3-5-4-8-4H56c-7 0-12 6-12 12v293c0 6 5 12 12 12h205c7 0 12-6 12-12V78c0-3 0-5-2-7zM56 305V12h144v64c0 3 3 6 6 6h55v223H56z');
            fileSVG.appendChild(fileSVGPath);
            domFileTpl.appendChild(fileSVG);
            let divfilename = document.createElement('div');
            divfilename.classList.add('file-name');
            divfilename.classList.add('gradient-text');
            divfilename.innerHTML = name;
            divfilename.title = name;
            domFileTpl.appendChild(divfilename);
            let divfilectrl = document.createElement('div');
            divfilectrl.classList.add('file-ctrl');
            domFileTpl.appendChild(divfilectrl);
            return domFileTpl;
        }
    </script>
    <!-- Draging -->
    <script>
        const dropHandler = (e) => {
            e.preventDefault();
            if(e.dataTransfer.files && e.dataTransfer.files.length>0){
                document.querySelector('input[type="file"]').files = e.dataTransfer.files;
                document.querySelector('input[type="file"]').dispatchEvent(new Event('change'));
            }
        }
        const dragOverHandler = (e) => {
            e.preventDefault();
        }
    </script>
</body>
</html>
